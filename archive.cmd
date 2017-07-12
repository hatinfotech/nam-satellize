set _my_datetime=%date%_%time%
set _my_datetime=%_my_datetime: =_%
set _my_datetime=%_my_datetime::=%
set _my_datetime=%_my_datetime:/=_%
set _my_datetime=%_my_datetime:.=_%

bin\7zr64.exe a archive[%_my_datetime%].7z . -x!*.idea -x!*.git -x!Data -x!Application\Data -x!*.7z