@echo off
set /p cdir=������Ҫ���Ƶ��ļ�����Ŀ¼:
if exist ../%cdir% (goto :copy) else (goto :err)


:copy
rd /s /q .\zencode\src


REM ֻ�����ļ�
\nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir%


REM �����ļ���ɾ���ļ��е�ע��
REM \nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir% 1


set datavar=%date:~0,10%
date 2010/01/08

"C:\Program Files\Zend\Zend Guard - 5.5.0\plugins\com.zend.guard.core.resources.win32.x86_5.5.0\resources\GuardEngine.exe" --xml-file .\guard.xml

date %datavar%
echo ������ɣ����Ա���á�
goto :bye


:err
echo ����ʧ����û�б��롣
pause
exit


:bye
rd /s /q .\data
start .\zencode\src
pause