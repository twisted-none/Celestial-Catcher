{$mode objfpc}
{$H+}

program LegacyDataGenerator;

uses
  sysutils, classes, dateutils;

var
  csvFile: TextFile;
  i: Integer;
  timestampStr: String;
  isSystemOk: Boolean;
  metricValue: Double;
  logMessage: String;
  filename: String;

begin
  filename := 'legacy_export.csv';
  
  AssignFile(csvFile, filename);
  Rewrite(csvFile);

  // 1. Заголовки CSV
  Writeln(csvFile, 'Timestamp;IsActive;Value;Message');

  Randomize;

  WriteLn('Pascal Legacy: Starting data generation...');

  for i := 1 to 50 do
  begin
    timestampStr := FormatDateTime('yyyy-mm-dd hh:nn:ss', Now - Random(100) / 100);

    isSystemOk := (Random(10) > 2);

    metricValue := Random * 1000;

    logMessage := 'System Check ID-' + IntToStr(i);

    Write(csvFile, timestampStr, ';');
    
    if isSystemOk then
      Write(csvFile, 'ИСТИНА;')
    else
      Write(csvFile, 'ЛОЖЬ;');
      
    Write(csvFile, FormatFloat('0.00', metricValue), ';');
    Writeln(csvFile, logMessage);
  end;

  CloseFile(csvFile);
  WriteLn('Pascal Legacy: CSV generation completed -> ', filename);
end.