@echo off
rd /s /q .\zencode\src

set datavar=%date:~0,10%
date 2010/01/08

::date /t
::pause
::exit

"C:\Program Files\Zend\Zend Guard - 5.5.0\plugins\com.zend.guard.core.resources.win32.x86_5.5.0\resources\GuardEngine.exe" --xml-file .\guard-all.xml

date %datavar%

start .\zencode\src
pause