// Функционал для переключения отображения деталей
// Получаем элементы
const buttons = document.querySelectorAll('.toggle-details');
buttons.forEach(button => {
    button.addEventListener('click', () => {
        const parent = button.closest('.course');
        const table = parent.querySelector('.course-activities');
        const scoreBlock = parent.querySelector('.score-block'); // Получаем блок score-block

        // Переключаем класс 'show' для таблицы
        table.classList.toggle('show');

        // Переключаем класс 'show' для score-block
        if (scoreBlock) {
            scoreBlock.classList.toggle('show');
        }

        // Переключаем класс 'open' для кнопки
        button.classList.toggle('open');
    });
});

// Функционал для перетаскиваемой кнопки
const draggableButton = document.createElement('button');
draggableButton.classList.add('draggable-button');
draggableButton.textContent = 'Mprogress';
document.body.appendChild(draggableButton);

let isDragging = false;
let offsetX = 0;
let offsetY = 0;

draggableButton.addEventListener('mousedown', (e) => {
    isDragging = true;
    offsetX = e.clientX - draggableButton.getBoundingClientRect().left;
    offsetY = e.clientY - draggableButton.getBoundingClientRect().top;
    draggableButton.style.position = 'absolute';
    draggableButton.style.zIndex = 1000;
});

document.addEventListener('mousemove', (e) => {
    if (isDragging) {
        draggableButton.style.left = `${e.clientX - offsetX}px`;
        draggableButton.style.top = `${e.clientY - offsetY}px`;
    }
});

document.addEventListener('mouseup', () => {
    isDragging = false;
});
document.addEventListener('DOMContentLoaded', () => {
// Логика для смены языка
const languageButton = document.querySelector('.language-toggle');
languageButton.addEventListener('click', () => {
    alert('Смена языка пока не реализована.');
    // Здесь можно добавить функционал переключения языка
});

// Логика для кнопки информации
const infoButton = document.querySelector('.info-button');
infoButton.addEventListener('click', () => {
    alert('Информация о дашборде: здесь отображается ваш прогресс в курсах.');
});
});


document.addEventListener("DOMContentLoaded", function() {
    // Получаем все элементы с классом .circle-progress-bar
    const progressBars = document.querySelectorAll('.circle-progress-bar');

    // Обрабатываем каждый прогресс-бар
    progressBars.forEach(function(bar, index) {
        // Извлекаем значение прогресса из data-атрибута
        const progress = parseInt(bar.getAttribute('data-progress'));

        // Ищем circle, который представляет прогресс
        const progressCircle = bar.querySelector('.circle-progress');
        const progressText = bar.querySelector('.circle-progress-text');
        const gradientContainer = bar.querySelector('defs'); // Находим контейнер <defs> для градиента

        // Создаем уникальный ID для каждого градиента
        const gradientId = `progress-gradient-${index}`;
        
        // Создаем новый элемент <linearGradient>
        const newGradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
        newGradient.setAttribute('id', gradientId);
        newGradient.setAttribute('x1', '0%');
        newGradient.setAttribute('y1', '0%');
        newGradient.setAttribute('x2', '0%');
        newGradient.setAttribute('y2', '100%');
        
        // Определяем цвета в зависимости от прогресса
        let startColor, endColor;
        if (progress == 0) {
            startColor = "#EAEAEA";  
            endColor = "white";
        } else if (progress >= 75) {
            startColor = "lime";  
            endColor = "white";
        } else if (progress >= 50) {
            startColor = "yellow";  
            endColor = "white";     
        } else {
            startColor = "orange";  
            endColor = "white";     
        }

        // Добавляем два <stop> элемента для градиента
        const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop1.setAttribute('offset', '0%');
        stop1.setAttribute('style', `stop-color:${startColor}; stop-opacity:1`);
        newGradient.appendChild(stop1);

        const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop2.setAttribute('offset', '100%');
        stop2.setAttribute('style', `stop-color:${endColor}; stop-opacity:1`);
        newGradient.appendChild(stop2);

        // Добавляем новый градиент в контейнер <defs>
        gradientContainer.appendChild(newGradient);

        // Применяем новый градиент к прогресс-бару
        progressCircle.setAttribute('stroke', `url(#${gradientId})`);

        // Изменяем текст, отображающий процент
        if (progress === 0) {
            progressText.innerHTML = "<span><p class ='circle-progress-text-mini' style='front-size:15px;'>Начните проходить курс</p></span>";
        } else {
            progressText.innerHTML = "<span>" + progress + "%</span><p class='circle-progress-text-mini' style='font-size: 15px;'>Успеваемость</p>";
        }
        
        // Прогресс всегда на 100%, так как бар всегда заполняется
        const circumference = 2 * Math.PI * 90; // длина окружности
        progressCircle.style.strokeDasharray = circumference;
        progressCircle.style.strokeDashoffset = 0; // Прогресс всегда на 100%
    });
});
        // Получаем элементы
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const modal = document.getElementById("myModal");

        // Функция для открытия модального окна
        openModalBtn.addEventListener("click", function() {
            modal.classList.add("show"); // Добавляем класс для отображения окна
        });

        // Функция для закрытия модального окна
        closeModalBtn.addEventListener("click", function() {
            modal.classList.remove("show"); // Убираем класс для скрытия окна
        });

        // Закрытие модального окна при клике вне его
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.classList.remove("show"); // Скрываем окно, если кликнули вне его
            }
        });
        const button = document.querySelector('.draggable-button');

button.addEventListener('click', () => {
    button.classList.add('hidden'); // Добавляем класс, который скрывает кнопку
});








