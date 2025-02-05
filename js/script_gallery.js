let products; // Делаем переменную products глобальной
let artistsData = {};

window.onload = () => {
  fetch('php/get_tracks.php') // Запрос к PHP файлу для получения данных о треках
    .then(response => response.json()) // Преобразуем ответ в формат JSON
    .then(tracks => {
      if (!Array.isArray(tracks)) {
        console.error("Неверные данные от сервера", tracks);
        return;
      }

      console.log("Полученные данные от сервера:", tracks);

      // Сохраняем данные в переменной products
      products = { data: tracks };

      // Заполняем объект artistsData минимальной и максимальной датой для каждого исполнителя
      tracks.forEach(track => {
        const artist = track.category?.trim().toLowerCase();  // Защищаем от null или undefined
        const releaseDate = parseInt(track.releaseDate, 10);  // Преобразуем строку в число

        if (!artist) {
          console.error("Исполнитель не указан для трека:", track);
          return;
        }
        if (isNaN(releaseDate)) {
          console.error("Неверная дата для трека:", track);
          return;
        }

        // Проверка на существование данных для исполнителя
        if (!artistsData[artist]) {
          artistsData[artist] = { minYear: releaseDate, maxYear: releaseDate };
        } else {
          artistsData[artist].minYear = Math.min(artistsData[artist].minYear, releaseDate);
          artistsData[artist].maxYear = Math.max(artistsData[artist].maxYear, releaseDate);
        }
      });

      console.log("Полные данные для исполнителей:", artistsData);

      // Создаем карточки треков
      createCards(products);

      // Обновляем чекбоксы фильтра с учетом данных о годах
      updateCheckboxes(artistsData);

      // Показываем все треки по умолчанию
      filterProduct("Все треки");

      // Начальная блокировка чекбоксов после загрузки данных
      document.getElementById("release-date-min").addEventListener("input", function() {
        const minDate = parseInt(this.value);
        console.log("Минимальная дата:", minDate); // Логируем значение ползунка
        document.getElementById("release-date-min-value").innerText = minDate;
        updateCheckboxes(artistsData);
      });

      document.getElementById("release-date-max").addEventListener("input", function() {
        const maxDate = parseInt(this.value);
        console.log("Максимальная дата:", maxDate); // Логируем значение ползунка
        document.getElementById("release-date-max-value").innerText = maxDate;
        updateCheckboxes(artistsData);
      });
    })
    .catch(err => {
      console.error('Ошибка при получении данных:', err);
    });
};

// Функция для создания карточек на основе данных products
function createCards(products) {
  let container = document.getElementById("products");
  container.innerHTML = ''; // Очистить контейнер перед добавлением новых карточек

  products.data.forEach(track => {
    let card = document.createElement("div");
    card.classList.add("card", track.category.replace(/\s+/g, "_"), "hide");

    // Контейнер для изображения
    let imgContainer = document.createElement("div");
    imgContainer.classList.add("image-container");

    // Элемент изображения
    let image = document.createElement("img");
    image.setAttribute("src", track.image ? `../uploads/images/${track.image}` : 'default-image.png');
    imgContainer.appendChild(image);
    card.appendChild(imgContainer);

    // Контейнер для текста
    let container = document.createElement("div");
    container.classList.add("container");

    // Название трека
    let name = document.createElement("h5");
    name.classList.add("track-name");
    name.innerText = track.trackName.toUpperCase(); // Используем данные из БД
    container.appendChild(name);

    // Цена
    let price = document.createElement("h6");
    price.innerText = "$" + track.price; // Используем данные из БД
    container.appendChild(price);

    card.appendChild(container);
    document.getElementById("products").appendChild(card); // Добавляем карточку на страницу
  });
}

// Функция фильтрации треков по категории
function filterProduct(value) {
  let buttons = document.querySelectorAll(".button-value"); // Находим все элементы с классом button-value

  // Добавляем/удаляем класс "active" у кнопок фильтрации
  buttons.forEach((button) => {
    if (value === button.innerText) {
      button.classList.add("active");
    } else {
      button.classList.remove("active");
    }
  });

  // Преобразуем пробелы в категории в нижнее подчеркивание
  let formattedValue = value.replace(/\s+/g, "_");

  // Фильтрация карточек
  let elements = document.querySelectorAll(".card"); // Находим все элементы с классом card
  elements.forEach((element) => {
    if (value === "Все треки") {
      element.classList.remove("hide"); // Показываем все карточки
    } else {
      if (element.classList.contains(formattedValue)) {
        element.classList.remove("hide"); // Показываем карточки с соответствующей категорией
      } else {
        element.classList.add("hide"); // Скрываем карточки, которые не относятся к выбранной категории
      }
    }
  });
}

// Обработчик для кнопки поиска
document.getElementById("search").addEventListener("click", () => {
  let searchInput = document.getElementById("search-input").value.toUpperCase();
  let elements = document.querySelectorAll(".track-name");
  let cards = document.querySelectorAll(".card");

  elements.forEach((element, index) => {
    if (element.innerText.includes(searchInput)) {
      cards[index].classList.remove("hide");
    } else {
      cards[index].classList.add("hide");
    }
  });
});

// Функция для переключения меню фильтрации
function toggleFilterMenu(event) {
  const filterMenu = document.getElementById("filter-menu");
  filterMenu.classList.toggle("hidden");
  event.stopPropagation(); // Остановить всплытие события, чтобы клик на кнопке не закрывал меню
}

// Закрытие меню при клике вне его
window.addEventListener("click", function (event) {
  const filterMenu = document.getElementById("filter-menu");
  if (!filterMenu.classList.contains("hidden") && !event.target.closest("#filter-menu") && !event.target.closest("#filter-toggle")) {
    filterMenu.classList.add("hidden");
  }
});

// Добавление обработчика для кнопки "Фильтр"
document.getElementById("filter-toggle").addEventListener("click", toggleFilterMenu);

// Обработчик для чекбокса "Выбрать всех"
document.getElementById("select-all").addEventListener("change", function () {
  let checkboxes = document.querySelectorAll(".artist-checkbox");
  checkboxes.forEach(checkbox => {
    checkbox.checked = this.checked; // Установить состояние всех чекбоксов в соответствии с состоянием "Выбрать всех"
  });
});

// Функция для применения фильтров
function applyFilters() {
  let minPrice = parseFloat(document.getElementById("price-min").value) || 0;
  let maxPrice = parseFloat(document.getElementById("price-max").value) || Infinity;

  let minDate = parseInt(document.getElementById("release-date-min").value) || 0;
  let maxDate = parseInt(document.getElementById("release-date-max").value) || Infinity;

  let selectedArtists = Array.from(document.querySelectorAll(".artist-checkbox:checked")).map(checkbox => checkbox.value);

  let elements = document.querySelectorAll(".card");

  elements.forEach((element) => {
    let itemPrice = parseFloat(element.querySelector("h6").innerText.replace("$", ""));

    let trackNameElement = element.querySelector(".track-name");
    let trackName = trackNameElement.innerText.trim().toUpperCase();

    // Получаем трек из данных products
    let track = products.data.find(track => track.trackName.trim().toUpperCase() === trackName);

    if (!track) {
      console.warn(`Трек с названием "${trackName}" не найден в данных products.data.`);
      return; // Если трек не найден, пропускаем этот элемент
    }

    let trackDate = track ? parseInt(track.releaseDate) : null;

    let isPriceVisible = itemPrice >= minPrice && itemPrice <= maxPrice;
    let isDateVisible = trackDate && trackDate >= minDate && trackDate <= maxDate;
    let isArtistVisible = selectedArtists.length === 0 || selectedArtists.includes(track.category);

    if (isPriceVisible && isDateVisible && isArtistVisible) {
      element.classList.remove("hide");
    } else {
      element.classList.add("hide");
    }
  });
}

// Функция для обновления доступности чекбоксов
function updateCheckboxes(artistsData = {}) {
  if (!artistsData || typeof artistsData !== "object") {
    console.error("artistsData не определен или не является объектом.");
    return;
  }

  console.log("artistsData:", artistsData); // Логирование объекта artistsData

  const minDate = parseInt(document.getElementById("release-date-min").value, 10);
  const maxDate = parseInt(document.getElementById("release-date-max").value, 10);

  document.querySelectorAll(".artist-checkbox").forEach(checkbox => {
    const artistName = checkbox.value.trim().toLowerCase();
    console.log("Имя исполнителя из чекбокса:", artistName);

    if (!artistsData.hasOwnProperty(artistName)) {
      console.error(`Нет данных для исполнителя: ${artistName}`);
      checkbox.disabled = true;
      checkbox.checked = false;
      return;
    }

    const artistData = artistsData[artistName];
    console.log("Данные для исполнителя из artistsData:", artistData);

    if (artistData.minYear <= maxDate && artistData.maxYear >= minDate) {
      checkbox.disabled = false;
    } else {
      checkbox.disabled = true;
      checkbox.checked = false;
    }
  });
}


// Привязка функции updateCheckboxes к изменению ползунков даты
document.getElementById("release-date-min").addEventListener("input", updateCheckboxes);
document.getElementById("release-date-max").addEventListener("input", updateCheckboxes);

document.getElementById("release-date-min").addEventListener("input", function() {
  const minDate = parseInt(this.value);
  console.log("Минимальная дата:", minDate); // Логируем значение ползунка
  document.getElementById("release-date-min-value").innerText = minDate;
  updateCheckboxes();
});

document.getElementById("release-date-max").addEventListener("input", function() {
  const maxDate = parseInt(this.value);
  console.log("Максимальная дата:", maxDate); // Логируем значение ползунка
  document.getElementById("release-date-max-value").innerText = maxDate;
  updateCheckboxes();
});
