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
  // Файл будет создаваться в папке /data (внутри контейнера)
  // В реальном проекте эту папку можно примонтировать как Volume
  filename := 'legacy_export.csv';
  
  AssignFile(csvFile, filename);
  Rewrite(csvFile);

  // 1. Заголовки CSV
  Writeln(csvFile, 'Timestamp;IsActive;Value;Message');

  Randomize;

  WriteLn('Pascal Legacy: Starting data generation...');

  // Генерируем 50 строк данных
  for i := 1 to 50 do
  begin
    // Требование 1: Время и даты timestamp
    // Формат: YYYY-MM-DD HH:MM:SS
    timestampStr := FormatDateTime('yyyy-mm-dd hh:nn:ss', Now - Random(100) / 100);

    // Требование 2: Логические блоки ИСТИНА и ЛОЖЬ
    isSystemOk := (Random(10) > 2); // 80% true

    // Требование 3: Числовой формат
    metricValue := Random * 1000;

    // Требование 4: Строки - текст
    logMessage := 'System Check ID-' + IntToStr(i);

    // Запись строки в CSV (разделитель точка с запятой)
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