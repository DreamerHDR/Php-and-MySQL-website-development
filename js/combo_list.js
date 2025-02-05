const combos = [
    {
        id: 1,
        character: "Johnny Cage",
        image: "../image/char_list/Johnny cage.webp",
        kameo: "none",
        damage: "280",
        combo_string: "212 * b3 * ff * 21+bf4"
    },
    {
        id: 2,
        character: "Johnny Cage",
        image: "../image/char_list/Johnny cage.webp",
        kameo: "none",
        damage: "270",
        combo_string: "212 * b3 * ff * 21+bd1"
    },
    {
        id: 3,
        character: "Johnny Cage",
        image: "../image/char_list/Johnny cage.webp",
        kameo: "none",
        damage: "280",
        combo_string: "f34 * b3 * 21+bf4"
    },
    {
        id: 4,
        character: "Johnny Cage",
        image: "../image/char_list/Johnny cage.webp",
        kameo: "none",
        damage: "320",
        combo_string: "214+db3T * 2 * 212 * f12+bf4"
    },
    {
        id: 5,
        character: "Mileena",
        image: "../image/char_list/mileena.webp",
        kameo: "none",
        damage: "300",
        combo_string: "12+bd4 * fu212+bd2"
    },
    {
        id: 6,
        character: "Mileena",
        image: "../image/char_list/mileena.webp",
        kameo: "scorpion",
        damage: "400",
        combo_string: "12+bd4 * fu212+bd2 * uK * fu212+bd2"
    },
    {
        id: 7,
        character: "Mileena",
        image: "../image/char_list/mileena.webp",
        kameo: "none",
        damage: "300",
        combo_string: "f24+bd4 * fu212+bd2"
    },
    {
        id: 8,
        character: "Mileena",
        image: "../image/char_list/mileena.webp",
        kameo: "scorpion",
        damage: "400",
        combo_string: "f24+bd4 * fu212+bd2 * uK * fu212+bd2"
    },
    {
        id: 9,
        character: "General Shao",
        image: "../image/char_list/shao.webp",
        kameo: "none",
        damage: "280",
        combo_string: "12+df4 * 22+df2"
    },
    {
        id: 10,
        character: "General Shao",
        image: "../image/char_list/shao.webp",
        kameo: "none",
        damage: "330",
        combo_string: "22+db3U * df4 * 22+df2"
    },
    {
        id: 11,
        character: "Liu Kang",
        image: "../image/char_list/lu kang.webp",
        kameo: "none",
        damage: "350",
        combo_string: "b23 * b23 * 333 * bf3"
    },
{
        id: 12,
        character: "Liu Kang",
        image: "../image/char_list/lu kang.webp",
        kameo: "none",
        damage: "380",
        combo_string: "b23 * b23 * 333+bf3A * bf3"
    },
{
        id: 13,
        character: "Liu Kang",
        image: "../image/char_list/lu kang.webp",
        kameo: "Khameleon",
        damage: "210",
        combo_string: "G * Kf * b23 * 333+bf3"
    },
{
        id: 14,
        character: "Baraka",
        image: "../image/char_list/Baraka.webp",
        kameo: "none",
        damage: "320",
        combo_string: "b32 * ff * b32 * 21 * df2"
    },
    {
        id: 15,
        character: "Baraka",
        image: "../image/char_list/Baraka.webp",
        kameo: "Cyrax",
        damage: "350",
        combo_string: "b32 * ff * b32 * 21 * df2A * db1 * K * db1"
    },
    {
        id: 16,
        character: "General Shao",
        image: "../image/char_list/shao.webp",
        kameo: "Sektor",
        damage: "330",
        combo_string: "22+K+db3 * df4 * 223"
    },
    {
        id: 17,
        character: "Sektor",
        image: "../image/char_list/sektor.webp",
        kameo: "none",
        damage: "290",
        combo_string: "11+db4 * uf212+db2 * uf23"
    },
    {
        id: 18,
        character: "Sektor",
        image: "../image/char_list/sektor.webp",
        kameo: "Motaro",
        damage: "330",
        combo_string: "11+bf2+K * b3+db4 * uf212+db2 * uf23"
    },
    {
        id: 19,
        character: "Sektor",
        image: "../image/char_list/sektor.webp",
        kameo: "Motaro",
        damage: "410",
        combo_string: "11+bf1+U+K * b3+db4 * uf212+db2 * uf23"
    },
    {
        id: 20,
        character: "General Shao",
        image: "../image/char_list/shao.webp",
        kameo: "Mavado",
        damage: "370",
        combo_string: "22+db3U * df4 * 223+db3+uK+22"
    },



]

// Объект для хранения выбранных фильтров
const selectedFilters = {
    character: [],
    kameo: [],
    damage: []
};









function displayCombos(filteredCombos) {
    const comboList = document.getElementById("combo-list");
    comboList.innerHTML = ""; // Очистка списка

    filteredCombos.forEach(combo => {
        const comboElement = document.createElement("div");
        comboElement.className = "combo-item";

        const kameoText = combo.kameo !== "none" ? ` + ${combo.kameo}` : "";

        comboElement.innerHTML = `
            <div class="combo-header">
                <img src="${combo.image}" alt="${combo.character}" class="character-image">
                <span>${combo.character}${kameoText}</span>
            </div>
            <div class="combo-divider"></div>
            <div class="combo-footer">
                Combo: ${combo.combo_string}
                <span class="damage-value">${combo.damage}</span>
            </div>
        `;

        comboList.appendChild(comboElement);
    });
}




// Функция для обновления фильтров при нажатии на чекбоксы
function updateFilter(event) {
    const checkbox = event.target;
    const key = checkbox.getAttribute("data-key");
    const value = checkbox.value;

    // Добавляем или удаляем значение из массива выбранных фильтров
    if (checkbox.checked) {
        selectedFilters[key].push(value);
    } else {
        selectedFilters[key] = selectedFilters[key].filter(item => item !== value);
    }

    // Обновляем доступность чекбоксов
    updateCheckboxes();

    // Обновляем список после изменения фильтров
    filterCombos(); 
}









// Инициализация событий при загрузке страницы
document.addEventListener("DOMContentLoaded", function() {
    // Отображаем полный список при загрузке страницы
    displayCombos(combos);

    // Добавляем события для чекбоксов
    const checkboxes = document.querySelectorAll(".dropdown-item input[type='checkbox']");
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateFilter);
    });

    // Добавляем обработчик для кнопки "Фильтр", чтобы показать или скрыть выпадающий список
    const filterButton = document.querySelector(".filter-button");
    filterButton.addEventListener("click", function() {
        const dropdown = document.getElementById("dropdown");
        dropdown.classList.toggle("show");
    });
});






function filterCombos() {
    const filteredCombos = combos.filter(combo => {
        // Проверка каждого ключа: если массив выбранных фильтров пуст, то не фильтруем по этому ключу
        return (!selectedFilters.character.length || selectedFilters.character.includes(combo.character)) &&
               (!selectedFilters.kameo.length || selectedFilters.kameo.includes(combo.kameo)) &&
               (!selectedFilters.damage.length || selectedFilters.damage.some(damageFilter => parseInt(combo.damage) >= parseInt(damageFilter)));
    });

    displayCombos(filteredCombos);
}




function updateCheckboxes() {
    const characterCheckboxes = document.querySelectorAll('input[data-key="character"]');
    const kameoCheckboxes = document.querySelectorAll('input[data-key="kameo"]');
    const damageCheckboxes = document.querySelectorAll('input[data-key="damage"]');

    const selectedCharacters = selectedFilters.character;
    const selectedKameos = selectedFilters.kameo;

    let validKameos = new Set();
    let validDamages = new Set();

    // Проверяем, если выбран персонаж
    if (selectedCharacters.length > 0) {
        const selectedCharacter = selectedCharacters[0];

        // Получаем комбинации для выбранного персонажа
        const characterCombos = combos.filter(combo => combo.character === selectedCharacter);

        characterCombos.forEach(combo => {
            validKameos.add(combo.kameo); // Добавляем доступные камео
            validDamages.add(combo.damage); // Добавляем доступный урон
        });

        // Деактивируем чекбоксы персонажей, кроме выбранного
        characterCheckboxes.forEach(checkbox => {
            checkbox.disabled = (checkbox.value !== selectedCharacter);
        });

        // Деактивируем чекбоксы камео
        kameoCheckboxes.forEach(checkbox => {
            checkbox.disabled = !(validKameos.has("none") && checkbox.value === "none") && !validKameos.has(checkbox.value);
        });

        // Если выбран какой-то камео, не разблокируем остальные
        if (selectedKameos.length > 0) {
            kameoCheckboxes.forEach(checkbox => {
                if (!selectedKameos.includes(checkbox.value)) {
                    checkbox.disabled = true;
                }
            });
        }

        // Блокируем чекбоксы урона
        damageCheckboxes.forEach(checkbox => {
            checkbox.disabled = Number(checkbox.value) > Math.max(...Array.from(validDamages).map(Number));
        });
        
    } else if (selectedKameos.length > 0) {
        // Если выбран камео, блокируем остальные камео
        const selectedKameo = selectedKameos[0];

        // Получаем персонажей для выбранного камео
        const kameoCombos = combos.filter(combo => combo.kameo === selectedKameo);

        kameoCombos.forEach(combo => {
            validDamages.add(combo.damage); // Добавляем доступный урон
        });

        // Деактивируем чекбоксы персонажей, которые не имеют комбинаций с выбранным камео
        characterCheckboxes.forEach(checkbox => {
            const hasValidCombo = kameoCombos.some(combo => combo.character === checkbox.value);
            checkbox.disabled = !hasValidCombo; // Блокируем только тех, у кого нет комбинаций
        });

        // Деактивируем чекбоксы камео (блокируем все, включая none)
        kameoCheckboxes.forEach(checkbox => {
            checkbox.disabled = (checkbox.value !== selectedKameo);
        });

        // Блокируем чекбоксы урона
        damageCheckboxes.forEach(checkbox => {
            checkbox.disabled = validDamages.size > 0 && Number(checkbox.value) > Math.max(...Array.from(validDamages).map(Number));
        });
    } else {
        // Если нет выбранных персонажей и камео, разблокируем все
        characterCheckboxes.forEach(checkbox => {
            checkbox.disabled = false; // Разблокируем всех персонажей
        });

        kameoCheckboxes.forEach(checkbox => {
            checkbox.disabled = false; // Разблокируем все камео
        });

        damageCheckboxes.forEach(checkbox => {
            checkbox.disabled = false; // Разблокируем все урон
        });
    }

    // Проверка на наличие выбранного персонажа и камео
    if (selectedCharacters.length > 0 && selectedKameos.length > 0) {
        const selectedCharacter = selectedCharacters[0];
        const selectedKameo = selectedKameos[0];

        // Получаем комбинации для выбранного персонажа и камео
        const validDamagesSet = new Set();

        const characterKameoCombos = combos.filter(combo =>
            combo.character === selectedCharacter && combo.kameo === selectedKameo
        );

        characterKameoCombos.forEach(combo => {
            validDamagesSet.add(combo.damage); // Добавляем доступный урон
        });

        // Блокируем чекбоксы урона
        damageCheckboxes.forEach(checkbox => {
            checkbox.disabled = !Array.from(validDamagesSet).some(damage => Number(checkbox.value) <= Number(damage));
        });
    }

    // Блокировка чекбоксов урона при выборе
    const selectedDamageCheckboxes = [...damageCheckboxes].filter(checkbox => checkbox.checked);
    if (selectedDamageCheckboxes.length > 0) {
        // Блокируем все остальные чекбоксы урона
        damageCheckboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.disabled = true;
            }
        });

        // Блокировка персонажей и камео при выборе урона
        const selectedDamageValue = Number(selectedDamageCheckboxes[0].value);
        characterCheckboxes.forEach(checkbox => {
            const combosForCharacter = combos.filter(combo => combo.character === checkbox.value);
            const hasValidCombo = combosForCharacter.some(combo => Number(combo.damage) >= selectedDamageValue);
            // Если уже выбран персонаж или камео, блокируем не валидные персонажи
            checkbox.disabled = !hasValidCombo || selectedCharacters.length > 0 || selectedKameos.length > 0; 
        });

        kameoCheckboxes.forEach(checkbox => {
            const combosForKameo = combos.filter(combo => combo.kameo === checkbox.value);
            const hasValidCombo = combosForKameo.some(combo => Number(combo.damage) >= selectedDamageValue);
            // Если уже выбран персонаж или камео, блокируем не валидные камео
            checkbox.disabled = !hasValidCombo || selectedCharacters.length > 0 || selectedKameos.length > 0; 
        });
    }
}




$(document).ready(function() {
    $("#shift_style").click(function(e) {
        e.preventDefault(); // Предотвращаем стандартное поведение ссылки
        const currentStyle = $("#theme-stylesheet").attr("href");

        // Если сейчас обычная версия, переключаем на печатную
        if (currentStyle === "../css/style_combo_list.css") {
            $("#theme-stylesheet").attr("href", "../css/style_print.css");
        } else {
            // Если сейчас печатная версия, возвращаем обычную
            $("#theme-stylesheet").attr("href", "../css/style_combo_list.css");
        }
    });
});









document.addEventListener("DOMContentLoaded", function() {
    // Проверка загрузки страницы
    console.log("Страница загружена");

    // Добавляем обработчик для кнопки "Фильтр", чтобы показать или скрыть выпадающий список
    const filterButton = document.querySelector(".filter-button");
    filterButton.addEventListener("click", toggleDropdown);
});

function toggleDropdown() {
    const dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("show"); // Добавляет или убирает класс 'show'
    console.log("Фильтр нажат, класс 'show' переключён");
}
