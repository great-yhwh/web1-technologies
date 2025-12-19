function print(label, data) {
    console.log(label, data);
    document.write(`
        <div style="font-family: monospace; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding: 10px;">
            <strong>${label}:</strong> ${JSON.stringify(data)}
        </div>
    `);
}

// Задача 1
function pickPropArray(arr, prop) {
    return arr.map(obj => obj[prop]).filter(Boolean);
}

const students = [
    { name: 'Павел', age: 20 },
    { name: 'Иван', age: 20 },
    { name: 'Эдем', age: 20 },
    { name: 'Денис', age: 20 },
    { name: 'Виктория', age: 20 },
    { age: 40 },
];

const result = pickPropArray(students, 'name');
print("Задача 1 (Имена)", result);


//  Задача 2
function createCounter() {
    let count = 0;
    return function () {
        count++;
        return count;
    };
}

const counter1 = createCounter();
print("Задача 2 (Счетчик 1 - вызов 1)", counter1());
print("Задача 2 (Счетчик 1 - вызов 2)", counter1());

const counter2 = createCounter();
print("Задача 2 (Счетчик 2 - вызов 1)", counter2());


// Задача 3
function spinWords(str) {
    const words = str.split(' ');
    const reversedWords = [];
    for (let word of words) {
        if (word.length >= 5) {
            word = word.split('').reverse().join('');
        }
        reversedWords.push(word);
    }
    return reversedWords.join(' ');
}

print("Задача 3 (Привет)", spinWords("Привет от Legacy"));
print("Задача 3 (Test)", spinWords("This is a test"));


// Задача 4
function getTarget(nums, target) {
    const map = new Map();
    for (let i = 0; i < nums.length; i++) {
        const needed = target - nums[i];
        if (map.has(needed)) return [map.get(needed), i];
        map.set(nums[i], i);
    }
    return [];
}

const nums = [2, 7, 11, 15];
const target = 18;
print("Задача 4 (Индексы)", getTarget(nums, target));


//  Задача 5
function getLongestLine(strs) {
    if (!strs || strs.length === 0) {
        return "";
    }

    let longest = "";

    for (let length = 2; length <= strs[0].length; length++) {
        for (let start = 0; start <= strs[0].length - length; start++) {

            let substring = strs[0].substring(start, start + length);

            if (strs.every(str => str.includes(substring)) && substring.length > longest.length) {
                longest = substring;
            }
        }
    }

    return longest;
}

const strs1 = ["цветок","поток","хлопок"];
print("Задача 5 (Пример 1)", getLongestLine(strs1));

const strs2 = ["собака","гоночная машина","машина"];
print("Задача 5 (Пример 2)", getLongestLine(strs2));