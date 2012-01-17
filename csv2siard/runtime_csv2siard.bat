@ECHO OFF
SETLOCAL

REM settings -------------------------------------------------------------------
SET UNIX_HOME=C:\Software\PCUnixUtils
SET PATH=%UNIX_HOME%;%PATH%

csv2siard.exe | grep version |  cut -d " " -f 8 >version.tmp
COPY $setversion$ + version.tmp $tmp$.bat > nul
CALL $tmp$.bat
DEL $tmp$.bat
DEL version.tmp

ECHO +++ csv2siard Version %VERSION% +++

SET RUNTIME=.\csv2siard_v.%VERSION%

REM copy -----------------------------------------------------------------------
ECHO .
REM RMDIR /S /Q %RUNTIME%

MKDIR %RUNTIME%
MKDIR %RUNTIME%\bin
MKDIR %RUNTIME%\source
MKDIR %RUNTIME%\csvdata
MKDIR %RUNTIME%\csvtest

COPY csv2siard.exe %RUNTIME%\bin

COPY xmllint.exe %RUNTIME%\bin
COPY iconv.dll %RUNTIME%\bin
COPY libxml2.dll %RUNTIME%\bin
COPY zlib1.dll %RUNTIME%\bin

COPY sablot.dll %RUNTIME%\bin
COPY expat.dll %RUNTIME%\bin

COPY crc32sum.exe %RUNTIME%\bin

COPY file.exe %RUNTIME%\bin
COPY magic.mgc %RUNTIME%\bin
REM COPY magic.mime %RUNTIME%\bin
COPY magic1.dll %RUNTIME%\bin
COPY regex2.dll %RUNTIME%\bin

COPY preferences.prefs %RUNTIME%\bin
COPY GPL-2.0_COPYING.txt %RUNTIME%\bin

COPY database-torque-4-0.xsd %RUNTIME%
COPY gv-model-v8.xml %RUNTIME%
COPY datatype-model.xml %RUNTIME%

COPY Anwendungshandbuch*.pdf %RUNTIME%

COPY *.php %RUNTIME%\source
COPY csv2siard.bcp %RUNTIME%\source

COPY csvdata\* %RUNTIME%\csvdata
COPY csvtest\* %RUNTIME%\csvtest

REM test -----------------------------------------------------------------------
CD %RUNTIME%\bin
DEL /Q ..\..\test.siard
SET PATH=

@ECHO ON
CALL csv2siard.exe
CALL csv2siard.exe ..\..\table2-model.xml ..\..\csvdata ..\..\test.siard
DEL /Q ..\..\test.siard
CALL csv2siard.exe no_db_model ..\..\csvdata ..\..\test.siard

@ECHO OFF
REM zip ------------------------------------------------------------------------
CD ..
%UNIX_HOME%\7z.exe a -mx9 %RUNTIME%.zip *
COPY %RUNTIME%.zip ..\%RUNTIME%.zip
DEL /Q %RUNTIME%.zip
CD ..
RMDIR /S /Q %RUNTIME%
