REST API c заданием и путями: <br>
1)	Создания задачи; URL/api/store (post)  <br>
2)	Получения статуса выполнения задачи; URL/api/show/{task} (get) <br>
3)	Остановка выполнения задачи; URL/api/stop/{task} (delete)<br>
4)	Создания группы задач; URL/api/storeGroup (post) <br>
5)	Получения статуса выполнения группы задач; URL/api/showGroup/{task} (get) <br>
6)	Остановка выполнения группы задач; URL/api/storeGroup (delete) <br> 
7)	Список всех задач; URL/api/index (get) <br>
 <br> <br>
Используется помимо сущностм "Задача" ("Task"), сущности "ProcessExecutionTaskTable" и "GroupTask" <br>
"ProcessExecutionTaskTable" Сущность в котором хранятся промежуточные хеши, и итоговый<br>
1)	string (хеш); <br>
2)	task_id; <br>
3)	group_id; <br>
"GroupTask" сущность содержит лишь название <br>
1)	name (мс); <br>
 <br>
Объект ProcessExecutionTask, Jobs использует QUEUE_CONNECTION=database. <br>
К сожалению я не смог найти, сделать чтобы "Частота обновления" были в милисекундах, реализованно в секундах. <br>
    return $this->release($this->task->frequency); <br>
    <br>
Использовал OpenServer, Windows 10, mysql. Миграции для всех сущностей есть. 
