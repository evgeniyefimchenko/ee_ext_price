﻿ee_ext_price - модуль расширяет функционал выгрузки прайсов. Добавляя возможность сортировки прайса по названию товара, выгрузки всех картинок товара и перекодируя файл в windows-1251 для возможности прямого открытия в WIN EXCEL
А так же установить проценку для каждого прайса.
Если в поле "Введите данные" проценки прайса на конце числа поставить знак % то будет высчитан процент, в ином случае расчёт ведётся в числовом эквиваленте.
Проценка может быть как положительной так и отрицательной, в зависимости от наличия знака - в начале числа.

Если в процессе установки модуля возникла ошибка 'Ошибка открытия файла app/addons/data_feeds/func.php для записи.' или 'Ошибка чтения файла app/addons/data_feeds/func.php' то значит
необходимый для работы хук ee_encode_file_price установлен не был и необходимо настроить права доступа скриптов модуля к папке app/addons Или же установить хук вручную.