@ECHO OFF
SETLOCAL

REM SET Null Soft Installer Path
SET NSIS=Q:\KOST\Software\NSIS

REM Make c2sGUI.exe
CALL %NSIS%\makensis.exe c2sGUI.nsi

CALL c2sGUI.exe
