var request = new XMLHttpRequest();

// Функція, яка виконується при зміні стану об'єкта XMLHttpRequest
request.onreadystatechange = function() {
    // Перевірка, чи об'єкт готовий та опрацьований
    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status == 200) {
            // Зміна вмісту елемента з ідентифікатором 'result' на відповідь від сервера
            document.getElementById('result').innerHTML = request.responseText;
        } 
    }
};

// Отримання елементу з ідентифікатором 'query'
var query = document.getElementById('query');

// Додавання обробника події для події 'keyup' на елементі 'query'
query.addEventListener('keyup', makeRequest);

// Функція для виконання запиту при введенні тексту в поле 'query'
function makeRequest() {
    var url = 'script.php?query=' + query.value;
    request.open('GET', url, true);
    request.send();
}
