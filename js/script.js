let main = function(){
    
    class Task{

        constructor(id, text, completed){
            this.id = id;
            this.text = text;
            this.completed = completed;
        }

        getHtmlBlock(){
            return `
            <div class="task" id='task${this.id}'>
                <h3 class="task-id">${this.id}.</h3>
                <div class="task-description">
                    <span>${this.text}</span>
                </div>
                <div class="controls">
                    <button class="btn-delete">Delete</button>
                    <div class="done-box">
                        <label for="done">Done</label>
                        <input type="checkbox" ${this.completed == 1 ? 'checked' : ''} name="done">
                    </div>
                </div>
            </div>
            `;
        }

        delete(){
            //Request to delete task
            let req = new XMLHttpRequest();
            let url = 'http://test.local/backend/api.php';


            req.open('DELETE', url);
            req.setRequestHeader("Content-Type", "application/json");
            req.onreadystatechange = function (e) {
                if (req.readyState == 4 && req.status == 200) {
                    let response = JSON.parse(req.responseText);
                    console.log(response);
                }
            }

            req.send(JSON.stringify({
                id : this.id
            }));
        }

        save(){
            //Request to edit task
            let req = new XMLHttpRequest();
            let url = 'http://test.local/backend/api.php';


            req.open('PUT', url);
            req.setRequestHeader("Content-Type", "application/json");
            req.onreadystatechange = function (e) {
                if (req.readyState == 4 && req.status == 200) {
                    let response = JSON.parse(req.responseText);
                    console.log(response);
                }
            }

            req.send(JSON.stringify({
                id : this.id,
                text : this.text,
                completed : this.completed
            }));
        }

        static getAll(){
            let req = new XMLHttpRequest();
            let url = 'http://test.local/backend/api.php';


            req.open('GET', url, true);
            req.onreadystatechange = function (e) {
                if (req.readyState == 4 && req.status == 200) {
                    let tasksJson = JSON.parse(req.responseText).Tasks;
                    let tasks = [];
                    tasksJson.forEach(el => {
                        tasks.push(new Task(el.id, el.text, el.completed));
                    })
                    console.log(tasksJson);
                    tasksReady(tasks);
                }
            }

            req.send(null);
            

            //return [ new Task('1', 'Tarea sin completar', 1)];

        }

        static createTask(text){
            let completed = 0;
            //REQUEST TO API
            let req = new XMLHttpRequest();
            let url = 'http://test.local/backend/api.php';


            req.open('POST', url);
            req.setRequestHeader("Content-Type", "application/json");
            req.onreadystatechange = function (e) {
                if (req.readyState == 4 && req.status == 200) {
                    let response = JSON.parse(req.responseText).Task;
                    console.log(response)
                    let taskCreated = new Task(response.id, response.text, response.completed);
                    taskCreated.insertIntoDOM();
                }
            }

            req.send(JSON.stringify({
                text, completed
            }));

            return new Task(100, text, 0);
        }

        insertIntoDOM(){
            let tasksListBlock = document.querySelector('.tasks-list');
            tasksListBlock.innerHTML += this.getHtmlBlock();
        }
    }

    Task.getAll();

    function tasksReady(tasks){
        let tasksListBlock = document.querySelector('.tasks-list');

        tasks.forEach(el => {
            tasksListBlock.innerHTML += el.getHtmlBlock();
        });

        //CREATE TASK
        let createTaskForm = document.querySelector('.create-task-form');
        createTaskForm.addEventListener('submit', function(e){
            e.preventDefault();
            let text = document.querySelector('.create-task-form input[name=description]').value;
            let taskCreated = Task.createTask(text);
            if(taskCreated != null){
                alert('Task Created!');
            }
        });

        //REGISTER DELETE CLICK EVENT
        let deleteButtons = document.querySelectorAll('.task .controls .btn-delete');
        deleteButtons.forEach((el) => {
            el.addEventListener('click', function(e){
                let taskDiv = getTaskFromControl(e.target);
                let id = getTaskId(taskDiv.id);
                let taskToDelete = tasks.find(el => el.id = id);
                console.log(taskToDelete);
                console.log(taskDiv);
                if(confirm(`¿Desea eliminar la tarea con id ${id}?`)){
                    taskToDelete.delete();
                    taskDiv.remove();
                }
            })
        });
        
        //REGISTER DONE CLICK EVENT

        let doneButtons = document.querySelectorAll('.task .controls .done-box input[name=done]');
        doneButtons.forEach(el => {
            el.addEventListener('click', function(e){
                let taskDiv = getTaskFromControl(e.target);
                let id = getTaskId(taskDiv.id);
                let taskToEdit = tasks.find(el => el.id = id);
                console.log(taskToEdit);
                console.log(taskDiv);
                let done = e.target.checked ? "1":"0";
                taskToEdit.completed = done;
                console.log(taskToEdit);
                taskToEdit.save();
            });
        });
    }

    
    function getTaskFromControl(control){
        let parentEl = control;
        while(parentEl = parentEl.parentElement){
            if(parentEl.classList.contains('task')){
                return parentEl;
            }
        }
    }

    function getTaskId(id){
        return id.replace('task', '');
    }

}();