WMIC PROCESS WHERE "name LIKE '%%run.bat%%'" CALL setpriority 32768
cd %CD%
%CD%\..\..\php.x64\php.exe %CD%\..\..\start.comparator_mysql.php
rem pause
exit