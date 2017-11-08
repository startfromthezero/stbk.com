@echo off
set /p cdir=请输入要复制的文件所在目录:
if exist ../%cdir% (goto :copy) else (goto :err)


:copy
rd /s /q .\zencode\src


REM 只复制文件
\nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir%


REM 复制文件并删除文件中的注解
REM \nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir% 1


set datavar=%date:~0,10%
date 2010/01/08

"C:\Program Files\Zend\Zend Guard - 5.5.0\plugins\com.zend.guard.core.resources.win32.x86_5.5.0\resources\GuardEngine.exe" --xml-file .\guard.xml

date %datavar%
echo 操作完成，并以编译好。
goto :bye


:err
echo 操作失败且没有编译。
pause
exit


:bye
rd /s /q .\data
start .\zencode\src
pause