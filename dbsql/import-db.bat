"C:\Program Files\7-Zip\7z.exe" e .\cyagps.7z

\nginx\mysql\bin\mysql.exe -h dc2.spt-tek.com -uroot -p"123456" --default-character-set=utf8 -e "DROP DATABASE cyagps;"

\nginx\mysql\bin\mysql.exe -h dc2.spt-tek.com -uroot -p"123456" --default-character-set=utf8 -e "CREATE DATABASE cyagps CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI;"

\nginx\mysql\bin\mysql.exe -h dc2.spt-tek.com -uroot -p"123456" --default-character-set=utf8 cyagps < .\cyagps.sql

del /Q .\cyagps.sql

