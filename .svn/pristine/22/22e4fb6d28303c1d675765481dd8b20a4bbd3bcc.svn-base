@ECHO OFF
set /p cdir=请输入要复制的文件所在目录:
if exist ../%cdir% (goto :copy) else (goto :err)


:copy
rd /S /Q .\data
rd /S /Q .\data_zend

REM 只复制文件
\nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir%

REM 复制文件并删除文件中的注解
REM \nginx\php\php.exe -q ./svn-copy.php svn-copy.txt %cdir% 1

"C:\Program Files\Zend\bin\zendenc5.exe" --quiet  --recursive --gui --ignore-errors --short-tags off --asp-tags off --optimizations 1023 --no-default-extensions --include-ext php --include-ext phtml --include-ext inc --include-ext php3 --include-ext php4 --include-ext php5 --ignore "CVS" --ignore ".cvsignore" ".\data" ".\data_zend"
echo 操作完成，并以编译好。
goto :bye


:err
echo 操作失败且没有编译。


:bye
start .\data\src\
