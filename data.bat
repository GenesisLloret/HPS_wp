@echo off
set output_file=data.md

REM Borrar el archivo data.md si ya existe
if exist %output_file% del %output_file%

REM Recorrer los archivos en la estructura
for /r %%f in (*.*) do (
    REM Escribir el nombre del archivo en el formato Markdown
    echo # File: %%f >> %output_file%
    echo ``` >> %output_file%

    REM Escribir el contenido del archivo
    type "%%f" >> %output_file%

    echo ``` >> %output_file%
    echo. >> %output_file%
)

echo Markdown data created in %output_file%
pause
