// Получаем все элементы input, которые переключают слайды
const slides = document.querySelectorAll('input[name="slider"]');
let currentSlide = 0; // Переменная для хранения текущего активного слайда

// Функция для переключения слайдов
function autoSlide() {
  currentSlide = (currentSlide + 1) % slides.length; // Следующий слайд (циклично)
  slides[currentSlide].checked = true; // Активируем соответствующий слайд
}

// Запускаем автоматическое переключение каждые 1 секунд
setInterval(autoSlide, 40000000000);


$(document).ready(function() {
	$("#shift_style").click(function(e) {
			e.preventDefault(); // Предотвращаем стандартное поведение ссылки
			const currentStyle = $("#theme-stylesheet").attr("href");

			// Если сейчас обычная версия, переключаем на печатную
			if (currentStyle === "css/style_about.css") {
					$("#theme-stylesheet").attr("href", "css/style_about_print.css");
			} else {
					// Если сейчас печатная версия, возвращаем обычную
					$("#theme-stylesheet").attr("href", "css/style_about.css");
			}
	});
});