;----------------------
!define HELP_Button               "Field 5"
!define CSV_DirReqest             "Field 4"
!define NO_DB_MODEL_RadioButton   "Field 7"
!define DB_MODEL_RadioButton      "Field 6"
!define DB_MODEL_FileRequest      "Field 8"
!define DB_MODEL_File             "Field 2"
!define EDIT_Button               "Field 12"
!define CREATE_Button             "Field 11"
!define PREFS_FileReqest          "Field 9"

;--------------------------------
Function ShowDialog
  WriteINIStr $DIALOG "${CSV_DirReqest}" "State" $CSV_FOLDER
  WriteINIStr $DIALOG "${PREFS_FileReqest}" "State" $PREFS_FILE
  ${If} $MODEL_SWITCH == 1
    WriteINIStr $DIALOG "${NO_DB_MODEL_RadioButton}" "State" 1
    WriteINIStr $DIALOG "${DB_MODEL_RadioButton}" "State" 0
    WriteINIStr $DIALOG "${DB_MODEL_File}" "Text" $OUT_PATH\\no_db_model.xml
    WriteINIStr $DIALOG "${DB_MODEL_FileRequest}" "State" ""
  ${Else}
    WriteINIStr $DIALOG "${NO_DB_MODEL_RadioButton}" "State" 0
    WriteINIStr $DIALOG "${DB_MODEL_RadioButton}" "State" 1
    WriteINIStr $DIALOG "${DB_MODEL_File}" "Text" ""
    WriteINIStr $DIALOG "${DB_MODEL_FileRequest}" "State" $DB_MODEL
  ${EndIf}
  InstallOptions::initDialog $DIALOG
  Pop $HWND
  ; set button "Back" invisible 
  GetDlgItem $1 $HWNDPARENT 3
  ShowWindow $1 0
  InstallOptions::show
FunctionEnd

;--------------------------------
Function LeaveDialog
  ReadINIStr $CSV_FOLDER $DIALOG "${CSV_DirReqest}" "State"
  StrCpy $OUT_PATH $CSV_FOLDER
  ReadINIStr $PREFS_FILE $DIALOG "${PREFS_FileReqest}" "State"
  ReadINIStr $0 $DIALOG "Settings" "State"
  
  ${Switch} "Field $0"
    ${Case} '${NO_DB_MODEL_RadioButton}'
      StrCpy $MODEL_SWITCH 1
      ReadINIStr $1 $DIALOG '${DB_MODEL_RadioButton}' 'HWND'
      SendMessage $1 ${BM_SETCHECK} 0 0
      ReadINIStr $1 $DIALOG '${DB_MODEL_FileRequest}' 'HWND'
      SendMessage $1 ${WM_SETTEXT} 1 'STR:'
      ReadINIStr $1 $DIALOG '${DB_MODEL_File}' 'HWND'
      SendMessage $1 ${WM_SETTEXT} 1 'STR:$OUT_PATH\no_db_model.xml'
      
      Abort
    ${Break}
    
    ${Case} '${DB_MODEL_RadioButton}'
      StrCpy $MODEL_SWITCH 2
      ReadINIStr $1 $DIALOG '${NO_DB_MODEL_RadioButton}' 'HWND'
      SendMessage $1 ${BM_SETCHECK} 0 0
      ReadINIStr $1 $DIALOG '${DB_MODEL_FileRequest}' 'HWND'
      SendMessage $1 ${WM_SETTEXT} 1 'STR:$DB_MODEL'
      ReadINIStr $1 $DIALOG '${DB_MODEL_File}' 'HWND'
      SendMessage $1 ${WM_SETTEXT} 1 'STR:'
      Abort
    ${Break}
    
    ${Case} '${EDIT_Button}'
      ${If} ${FileExists} $PREFS_FILE
        ExecWait '"notepad.exe" "$PREFS_FILE"'
      ${Else}
        MessageBox MB_OK 'Achtung: keine oder keine g�ltige Pr�ferenzdatei gew�hlt$\n$PREFS_FILE'
      ${EndIf}
      Abort
    ${Break}
    
    ${Case} '${CREATE_Button}'
      StrCpy $PAGE_NO 1
      StrCpy $R9 -1
      Call RelGotoPage
    ${Break}
    
    ${Case} '${HELP_Button}'
      ExecShell "open" "$EXEDIR\${CSV2SIARDDHELP}"
      Abort
    ${Break}
    
    ${Case} '${CSV_DirReqest}'
      ${If} $MODEL_SWITCH == 1
        ReadINIStr $1 $DIALOG '${DB_MODEL_File}' 'HWND'
        SendMessage $1 ${WM_SETTEXT} 1 'STR:$OUT_PATH\no_db_model.xml'
      ${EndIf}
      Abort
    ${Break}
    
    ${Default}
      Call RunCSV2SIARD
      Call SaveSettings
      Abort
    ${Break}
  ${EndSwitch}
FunctionEnd

;--------------------------------
VAR MODEL
Var TWEEK

Function RunCSV2SIARD
  ${IfNot} ${FileExists} $CSV_FOLDER
    MessageBox MB_OK "Achtung: das gew�hlte CSV Verzeichnis existiert nicht$\n$CSV_FOLDER"
    Abort
  ${Else}
    StrCpy $OUT_PATH $CSV_FOLDER
  ${EndIf}
  
  ; ${GetFileAttributes} $OUT_PATH "READONLY" $R0
  GetTempFileName $TWEEK $OUT_PATH
  fileOpen $0 $TWEEK w
    fileWrite $0 $TWEEK
  fileClose $0
  ${IfNot} ${FileExists} $TWEEK
    MessageBox MB_OK "Achtung: in das Verzeichnis $OUT_PATH kann nicht geschrieben werden$\nes wird stattdessen auf den Desktop geschrieben"
    StrCpy $OUT_PATH $DESKTOP
  ${Else}
    Delete $TWEEK
  ${EndIf}
  
  ${If} $MODEL_SWITCH == 1
    StrCpy $MODEL ":NO_DB_MODEL=$OUT_PATH\no_db_model.xml"
    ReadINIStr $1 $DIALOG '${DB_MODEL_File}' 'HWND'
    SendMessage $1 ${WM_SETTEXT} 1 'STR:$OUT_PATH\no_db_model.xml'
  ${Else}
    ReadINIStr $DB_MODEL $DIALOG "${DB_MODEL_FileRequest}" "State"
    StrCpy $MODEL $DB_MODEL
    ${IfNot} ${FileExists} $DB_MODEL
      MessageBox MB_OK "Achtung: das ausgew�hlte Daten Modell existiert nicht$\n$DB_MODEL"
      Abort
    ${EndIf}
  ${EndIf}
  
  ${IfNot} ${FileExists} $PREFS_FILE
    MessageBox MB_OK 'Achtung: keine oder keine g�ltige Pr�ferenzdatei gew�hlt$\n$PREFS_FILE'
    Abort
  ${EndIf}

  Push $CSV_FOLDER
  Call GetBaseName
  Pop $0
  StrCpy $SIARD_FILE "$OUT_PATH\$0.siard"
  ${If} ${FileExists} $SIARD_FILE
    MessageBox MB_YESNO 'Achtung: soll die folgende SIARD Datei �berschrieben werden?$\n"$SIARD_FILE"' IDYES overwrite IDNO cancel
cancel:
    Abort
overwrite:
    Delete "$SIARD_FILE"
  ${EndIf}
  
  ExecWait '"${CSV2SIARD}" "$MODEL" "$CSV_FOLDER" "$SIARD_FILE" "$PREFS_FILE" ":LOG_FILE=$LOG"' $0
  ${If} $0 == 0
    MessageBox MB_OK 'Die folgende SIARD Datei wurde erfolgreich angelegt:$\n"$SIARD_FILE"';
  ${Else}
    MessageBox MB_OK 'Achtung: ein Fehler ist aufgetreten'
  ${EndIf}
  ExecWait '"notepad.exe" "$LOG"'
  
FunctionEnd

;--------------------------------
