<?php

// Вычисляем число дней в текущем месяце
$dayofmonth = date('t',$ttime);
// Счётчик для дней месяца
$day_count = 1;

// 1. Первая неделя
$num = 0;
for($i = 0; $i < 7; $i++)
{
    // Вычисляем номер дня недели для числа
    $dayofweek = date('w',
        mktime(0, 0, 0, date('m',$ttime), $day_count, date('Y',$ttime)));
    // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
    $dayofweek = $dayofweek - 1;
    if($dayofweek == -1) $dayofweek = 6;

    if($dayofweek == $i)
    {
        // Если дни недели совпадают,
        // заполняем массив $week
        // числами месяца
        $week[$num][$i] = $day_count;
        $day_count++;
    }
    else
    {
        $week[$num][$i] = "";
    }
}

// 2. Последующие недели месяца
while(true)
{
    $num++;
    for($i = 0; $i < 7; $i++)
    {
        $week[$num][$i] = $day_count;
        $day_count++;
        // Если достигли конца месяца - выходим
        // из цикла
        if($day_count > $dayofmonth) break;
    }
    // Если достигли конца месяца - выходим
    // из цикла
    if($day_count > $dayofmonth) break;
}

// 3. Выводим содержимое массива $week
// в виде календаря
// Выводим таблицу
$calendar =  '<tbody>';
for($i = 0; $i < count($week); $i++)
{
    $calendar .= '<tr>';
    for($j = 0; $j < 7; $j++)
    {
        if(!empty($week[$i][$j]))
        {
            // Если имеем дело с субботой и воскресенья
            // подсвечиваем их
            if(in_array($week[$i][$j],$days))
                $calendar =  $calendar.'<td class="linked"><a href="index.php?viewby=calendar&amp;day='.$week[$i][$j].'&amp;month='.$month.'&amp;year='.$year.'">'.$week[$i][$j].'</a></td>';
            else $calendar =  $calendar.'<td>'.$week[$i][$j].'</td>';
        }
        else $calendar =  $calendar.'<td>&nbsp;</td>';
    }
    $calendar .= '</tr>';
}
$calendar .= '</tbody>';

?>
