
/* Общие стили для дашборда */
ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
.course {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.course h2 {
    font-size: 1.5rem;
    margin: 0 0 8px;
}
.course p {
    margin: 4px 0;
    font-size: 1rem;
}
progress {
    width: 100%;
    height: 16px;
    -webkit-appearance: none;
    appearance: none;
    border-radius: 8px;
    background-color: #f3f3f3;
    overflow: hidden;
}
progress::-webkit-progress-bar {
    background-color: #f3f3f3;
    border-radius: 8px;
}
progress::-webkit-progress-value {
    background-color: #4caf50;
    transition: width 0.5s ease;
}
progress[value]::-webkit-progress-value {
    background-color: linear-gradient(to right, red, yellow, green);
}
button.toggle-details {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    cursor: pointer;
    font-size: 1rem;
}
button.toggle-details:hover {
    background-color: #0056b3;
}
table.course-activities {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}
table.course-activities th,
table.course-activities td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}
table.course-activities th {
    background-color: #f4f4f4;
    font-weight: bold;
}
.course .progress-container {
    display: flex;
    align-items: center;
    gap: 8px;
}
.course .progress-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 1rem;
    color: #fff;
}
.progress-circle.green {
    background-color: #4caf50;
}
.progress-circle.yellow {
    background-color: #ffeb3b;
    color: #000;
}
.progress-circle.red {
    background-color: #f44336;
}
.course button.toggle-details {
    display: block;
    margin: 8px auto;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
}
.course button.toggle-details:hover {
    background-color: #0056b3;
}