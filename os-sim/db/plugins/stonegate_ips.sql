-- stonegate_ips
-- plugin_id: 1643
DELETE FROM plugin WHERE id = "1643";
DELETE FROM plugin_sid where plugin_id = "1643";


INSERT INTO plugin (id, type, name, description) VALUES (1643, 1, 'stonegate_ips', 'Stonegate IPS');

INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_2-Way-Fp-Match-Seq',4,1, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_Compress-Multiple-Matches',2,2, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_Compress-SIDs',2,3, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_DNS-Cache-Poisoning',3,4, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_Executable-Upload-After-Potential-Compromise',4,5, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_HTTP-Bad-Uri-Accepted',4,6, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_Kerberos-Brute-Force',3,7, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_Log-Flood-Protection',2,8, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_Microsoft-Windows-Smb-Server-Ntlm-Authentication-Bypass-CVE-2010-0231',3,9, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_SMB-Bidirectional-Authentication',1,10, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Analyzer_SMB-Brute-Force-Attack',3,11, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Client-Class-Unknown',1,12, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Client-TCP-Extra-Data',1,13, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Client-Type-Query-Only',1,14, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Client-Type-Unknown',1,15, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Client-UDP-Extra-Data',1,16, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_IQUERY-Reply-Failure',2,17, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_IQUERY-Request',2,18, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Server-Header-Z-Nonzero',1,19, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Server-Name-Bad-Label-Type',1,20, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Server-Name-Bad-Pointer-Version-2',1,21, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Server-TCP-Extra-Data',1,22, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Server-TCP-QR-Wrong-Direction',1,23, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Server-Type-Query-Only',1,24, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Transfer-Reply-Failure',2,25, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Transfer-Reply-Success',2,26, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS_Transfer-Request',2,27, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'DNS-UDP_Nameserver-Version-Query',1,28, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-Autonomy-Keyview-Excel-File-Sst-Parsing-Integer-Overflow',4,29, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-IBM-Lotus-Notes-HTML-Speed-Reader-Long-Url-Buffer-Overflow',3,30, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-JavaScript-In-PDF',3,31, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-Microsoft-OLE-Structured-Storage-Suspicious-File-Transfer',1,32, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-Microsoft-Windows-Kodak-Image-Viewer-Code-Execution',4,33, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-Mozilla-Command-Line-Url-Command-Execution',3,34, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_BS-PNG-Image-With-Large-Data-Length-Value',3,35, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_Content-Transfer-Encoding-Unknown',1,36, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_Encoding-Multipart-Invalid',1,37, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_HCS-BitDefender-AntiVirus-Logging-Function-Format-String',3,38, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_HCS-Elm-Expires-Header-Field-Buffer-Overflow',3,39, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_HCS-Malformed-Date-Header-Field',3,40, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_IMF-Multipart-Delimiter-Use-Invalid',1,41, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_IMF-Too-Long-Header-Field',1,42, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_IMF-Too-Long-Header',1,43, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'E-Mail_IMF-Too-Long-MIME-Subtype-Name',1,44, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_ALLO-Too-Big-Success',1,45, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Anonymous-Login-Attempt',1,46, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_EPSV-Too-Big',1,47, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_EPSV-Too-Big-Success',1,48, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Non-Ascii-Command-Argument',1,49, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Oversized-ALLO-Argument',1,50, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_PASV-Unused',1,51, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Reply-Code-Conflict',1,52, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Reply-Extra',1,53, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Reply-Syntax-Incorrect',1,54, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_SITE',2,55, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_SITE-Success',2,56, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'FTP_Transfer-Data-Premature',1,57, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Generic_FreeBSD-Nfsd-Nfs-Mount-Request-Denial-Of-Service',3,58, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Generic_HTTP-URI-Directory-Traversal',3,59, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Generic_Kerberos-Authentication-Failed',1,60, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Generic_UDP-Out-Of-State-DNS-Response-With-Additional-Record',2,61, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'GRE_Tunnel-Other-Protocol',2,62, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Apache-Backslash-Directory-Traversal',3,63, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Apache-WebDAV-Propfind-Access',1,64, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CHS-MailEnable-HTTP-Authorization-Header-Buffer-Overflow',3,65, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CRL-Possible-Script-In-Header',3,66, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CRL-Possible-Script-In-Request',3,67, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CS-Asn-1-Integer-BOF-MS04-007',3,68, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CS-Excessively-Long-Options-Request-Argument',4,69, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CS-Excessively-Long-Propfind-Request-Argument',4,70, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CS-Excessively-Long-Proppatch-Request-Argument',4,71, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CS-Excessively-Long-Request-Version-Field',4,72, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CS-Suspicious-HTTP-Authorization-Negotiate-Token',1,73, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CSU-MySQL-MaxDB-WebDBM-BOF-2',3,74, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_CSU-Php-Suspicious-External-Parameter-Reference',4,75, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_e107-Contact-Php-Remote-Code-Execution',4,76, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Executable-File-Within-Downloaded-MS-OLE',3,77, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_FunWebProducts-Activity',1,78, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_FunWebProducts-mywebsearch-Toolbar',1,79, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_IIS-Server-WebDAV-Xml-Request-DoS-MS04-030',3,80, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_JPG-Gdiplus-DLL-Comment-Buffer-Overflow',3,81, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Microsoft-Excel-Malformed-Imdata-Record',4,82, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Microsoft-Excel-Named-Graph-Record-Parsing-Stack-Overflow',4,83, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Microsoft-IIS-WebDAV-Source-Code-Disclosure',3,84, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Microsoft-Office-PowerPoint-Remote-Code-Execution-CVE-2010-0029',3,85, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Mozilla-CSS-Moz-Binding-Cross-Site-Scripting',1,86, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_MPack-Invisible-Inline-Frame',4,87, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Perl-Pipe-In-URI-Arg-2',3,88, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Reply-Content-Length-Duplicate',3,89, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Reply-Content-Length-Unparseable',3,90, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Request-Header-Line-Unparseable',1,91, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Request-Headers-Version-0.9',1,92, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Request-Unknown',1,93, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Request-URI-Missing',1,94, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Request-Version-0.9',1,95, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Request-Version-Not-HTTP',1,96, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTPS_CS-Plaintext-Request-In-HTTPS-Context',3,97, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SLS-Successful-Status-Code',2,98, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Adobe-Acrobat-Embedded-JBIG2-Stream-Buffer-Overflow',4,99, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Adobe-Flash-ActiveX-Buffer-Overflow',3,100, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Adobe-Multiple-Products-BMP-Image-Header-Handling-Buffer-Overflow',3,101, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Apple-iTunes-M3u-Playlist-Handling-Buffer-Overflow',4,102, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Apple-QuickTime-Image-Descriptor-Atom-Parsing-Memory-Corruption',4,103, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Autonomy-Keyview-Excel-File-Sst-Parsing-Integer-Overflow',4,104, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Internet-Explorer-CreateTextRange-Vulnerability-2',3,105, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-JavaScript-In-PDF',3,106, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-JavaScript-Self-Reference',1,107, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-JavaScript-Xor-Obfuscation-Method',1,108, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Launch-Command-In-PDF',3,109, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Macromedia-Shockwave-mwdir.dll-ActiveX-Control-Buffer-Overflow',4,110, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Embedded-OpenType-Font-Engine-Intenger-Overflow',4,111, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Excel-Conditional-Formatting-Values-Handling-Code-Execution',4,112, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Excel-File-Handling-Code-Execution-Vulnerability',4,113, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Excel-Frtwrapper-Record-Buffer-Overflow',4,114, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Excel-Merge-Cell-Record-Pointer-CVE-2010-3237',4,115, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Internet-Explorer-Invalid-Pointer-Reference',4,116, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Internet-Explorer-Invalid-Pointer-Reference-CVE-2010-0806',4,117, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Internet-Explorer-Object-Reference-Counting-Memory-Corruption',4,118, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Killbit-Disabled-ActiveX-Object',4,119, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Office-Drawing-Exception-Handling-CVE-2010-3335',4,120, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Office-MSO-Large-SPID-Read-AV-CVE-2010-3336',4,121, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Office-PowerPoint-FB1h-Parsing-BOF-CVE-2010-2572',3,122, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-OLE-Structured-Storage-Suspicious-File-Download',1,123, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-Word-File-Information-Memory-Corruption-MS09-068',4,124, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Microsoft-WordPad-Text-Converter-CVE-2010-2563',4,125, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Opera-Browser-File-URI-Handling-Buffer-Overflow',4,126, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_SS-Suspicious-Filename-In-Zip-Archive',3,127, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Suspicious-ICC-Profile-In-JPEG-File',3,128, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Suspiciously-Long-URI-Component',3,129, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Suspiciously-Long-URI-Component-With-Potential-Shellcode',3,130, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_Webroot-Exit',1,131, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'HTTP_WScript.Shell-ActiveX-Object-Local-Registry-Access',4,132, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'ICMP_Checksum-Mismatch',1,133, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'ICMP_Code-Unknown',1,134, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'IP_Datagram-Received',1,135, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'IP_Length-Inconsistency',1,136, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'IPv4_Version-Not-4',1,137, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'MSRPC-TCP_CPS-Microsoft-Endpoint-Mapper-Lookup-Request',3,138, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'MSRPC-TCP_CPS-Windows-Local-Security-Authority-Username-Disclosure',1,139, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'NetBIOS-TCP_SMB2-DFS-DOS-MS09-050',3,140, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'RFB-Client-Bad-Handshake-Message-Sequence',4,141, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Scan_Completed',2,142, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SIP_Message-No-Transaction',2,143, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_CHS-Authentication-Attempt',1,144, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_CHS-Malware-Filename-Access',3,145, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_CHS-Negotiate-Protocol-Request',2,146, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_CHS-Null-Session-Samr-Access',3,147, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_CHS-Samba-smbd-Flags2-Header-Parsing-DOS',4,148, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_Failed-Session-Setup',1,149, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMB-TCP_FW-Executable-File-Write',1,150, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Command-Syntax-Incorrect',1,151, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Command-Syntax-Incorrect-Success',1,152, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Command-Too-Long',1,153, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Command-Unknown-Local-Extension',1,154, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Command-Unknown-Local-Extension-Success',1,155, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_CS-Novell-Groupwise-Client-Img-Tag-Src-Parameter-Buffer-Overflow',4,156, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Help-Overflow',3,157, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Pipeline-Overflow',1,158, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_RCPT-TO-Parameters-Invalid',1,159, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_RCPT-TO-Parameters-Invalid-Success',1,160, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Routing-Source',1,161, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SMTP_Unknown-Command',1,162, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SNMP-UDP_Default-Community-String-Accepted',3,163, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SNMP-UDP_GetBulkRequest-With-Nonzero-Nonrepeaters-And-Maxrepeaters-Values',3,164, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SNMP-UDP_Linux-Kernel-SNMP-NAT-Helper-SNMP-Decode-DoS-2',1,165, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SNMP-UDP_Microsoft-SNMP-Service-Buffer-Overflow',4,166, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SNMP-UDP_Write-Attempt-Using-Default-Community-String',3,167, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SRP_CS-RIM-BlackBerry-Enterprise-Server-Router-Denial-Of-Service',3,168, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SSH-Client-Not-encrypted-data',4,169, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SSHv1_Client-Cipher-DES',1,170, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SSHv1_Server-Cipher-DES-Advertised',1,171, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'SSH-Violation',1,172, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_Checksum-Mismatch',1,173, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_FIN-Data-After',1,174, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_Initial-Window-Too-Large',2,175, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_Initial-Window-Too-Many-Segments',2,176, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_Segment-Content-Conflict',3,177, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_Segment-Invalid',1,178, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_SYN_Scan_Started',1,179, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'TCP_Urgent',2,180, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'Telnet_SCS-Microsoft-Telnet-Client-Information-Disclosure',1,181, 8, NULL, 1);
INSERT INTO plugin_sid (plugin_id, name, priority, sid, category_id, class_id, reliability) VALUES (1643, 'UDP_Checksum-Mismatch',1,182, 8, NULL, 1);
