@ECHO OFF
set /p cdir=������Ҫ���Ƶ��ļ�����Ŀ¼:
if exist ../%cdir% (goto :copy) else (goto :err)


:copy
rd /S /Q .\data
rd /S /Q .\data_zend

REM ֻ�����ļ�
\nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir%

REM �����ļ���ɾ���ļ��е�ע��
REM \nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir% 1

"C:\Program Files\Zend\bin\zendenc5.exe" --quiet  --recursive --gui --ignore-errors --short-tags off --asp-tags off --optimizations 1023 --no-default-extensions --include-ext php --include-ext phtml --include-ext inc --include-ext php3 --include-ext php4 --include-ext php5 --ignore "CVS" --ignore ".cvsignore" ".\data" ".\data_zend"
echo ������ɣ����Ա���á�
goto :bye


:err
echo ����ʧ����û�б��롣


:bye
start .\data\src\
