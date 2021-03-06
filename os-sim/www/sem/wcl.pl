#!/usr/bin/perl
use Time::Local;
use File::Basename; 

if(!$ARGV[2]){
print "Expecting: start_date end_date\n";
print "Don't forget to escape the strings\n";
exit;
}

%ini = read_ini();
$loc_db = $ini{'main'}{'locate_db'};
$loc_db = "/var/ossim/logs/locate.index" if ($loc_db eq "");

$debug="";
$user = $ARGV[0];
$start = $ARGV[1];
$end = $ARGV[2];
$debug = $ARGV[4];

############
###### Convert stuff
############

if ($start =~ /(\d+)-(\d+)-(\d+)\s+(\d+):(\d+):(\d+)/) {
	$start_epoch = timegm($6, $5, $4, $3, $2-1, $1);
}
if ($end =~ /(\d+)-(\d+)-(\d+)\s+(\d+):(\d+):(\d+)/) {
	$end_epoch = timegm($6, $5, $4, $3, $2-1, $1);
}

$common_date = `perl return_sub_dates_locate.pl \"$start\" \"$end\"`;
if ($debug ne "") { open (L,">>$debug"); }

#print "perl return_sub_dates.pl $start $end`;
chop($common_date);

%already = ();
$lines = 0;
$sort = ($order_by eq "date") ? "sort" : "sort -r";
$swish = "locate.findutils -d $loc_db $common_date | grep -E \".(log|log.gz)\$\" | php check_perms.php $user | $sort |";
print L "WCL.pl: calling $swish\n" if ($debug ne "");
open (G,$swish);
while ($file=<G>) {
	next if ($file =~ /Warning|\/searches\// || $file eq "");
	chomp($file);
	my @fields = split(/\//,$file);
	my $sdirtime = timegm(0, 0, $fields[7], $fields[6], $fields[5]-1, $fields[4]);
	my $edirtime = timegm(59, 59, $fields[7], $fields[6], $fields[5]-1, $fields[4]);
	print L "WCL.pl: $start <= ".$fields[4]."-".$fields[5]."-".$fields[6]." ".$fields[7]."h <= $end ?: " if ($debug ne "");
	if ($start_epoch<=$edirtime && $end_epoch>=$sdirtime) {
		my $sf = dirname($file)."/../.total_events_".$fields[8];
		my $ac = $fields[7]."-".$fields[6]."-".($fields[5]-1)."-".$fields[4]."-".$fields[8];
		#$sf =~ s/log$/ind/;
		print L "yes $fields[8] += " if ($debug ne "");
		if (!$already{$ac}++) {
			open (F,$sf);
			while (<F>) {
				#if (/^lines\:(\d+)/) {
				#	$lines += $1;
				#}
				if (/^(\d+)/) {
					$lines += $1; 
					print L "$1 = $lines\n" if ($debug ne "");
				}
			}
			close F;
		} else { print L "already\n" if ($debug ne ""); }
	} else { print L "no skip\n" if ($debug ne ""); }
}
close G;
if ($debug ne "") {close L;}
print "$lines\n";

sub read_ini {
	my ($hash,$section,$keyword,$value);
    open (INI, "everything.ini") || die "Can't open everything.ini: $!\n";
    while (<INI>) {
        chomp;
        if (/^\s*\[(\w+)\].*/) {
            $section = $1;
        }
        if (/^\W*(.+?)=(.+?)\W*(#.*)?$/) {
            $keyword = $1;
            $value = $2 ;
            # put them into hash
            $hash{$section}{$keyword} = $value;
        }
    }
    close INI;
    return %hash;
}