Репозиторий для WebИС-ЛБ-ПИ-20

<h1>Задачи по JavaScript</h1>
<p>Задания с * необязательные.</p>
<!-- ЗАДАНИЕ 1 -->
<section>
<h2>Задание 1</h2>
<p>
    Написать функцию <code>pickPropArray</code>, которая принимает 2 аргумента - массив с объектами и свойство объекта. 
    На выходе должен получиться массив со значениями объектов по заданному свойству, если такое свойство есть в этом объекте.
</p>
<pre><code>const students = [
{ name: 'Павел', age: 20 },
{ name: 'Иван', age: 20 },
{ name: 'Эдем', age: 20 },
{ name: 'Денис', age: 20 },
{ name: 'Виктория', age: 20 },
{ age: 40 },
]

const result = pickPropArray(students, 'name')

console.log(result) 
<span>// [ 'Павел', 'Иван', 'Эдем', 'Денис', 'Виктория' ]</span></code></pre>
</section>

<!-- ЗАДАНИЕ 2 -->
<section>
<h2>Задание 2</h2>
<p>
    Написать функцию <code>createCounter</code>, которая при вызове создает функцию, изменяющую локальную переменную <code>count</code>, 
    к которой нет доступа вне функции. То есть мы не можем получить доступ к самой <code>count</code>. 
    Также при вызове созданной функции необходимо выводить в консоль значение локальной переменной.
</p>
<pre><code>function createCounter() {
...

return function () {
...
}
}

const counter1 = createCounter()
counter1() <span>// 1</span>
counter1() <span>// 2</span>

const counter2 = createCounter()
counter2() <span>// 1</span>
counter2() <span>// 2</span></code></pre>
</section>

<!-- ЗАДАНИЕ 3 -->
<section>
<h2>Задание 3</h2>
<p>
    Напишите функцию <code>spinWords</code>, которая принимает строку из одного или нескольких слов и возвращает ту же строку, 
    но с перевернутыми словами из пяти или более букв.
</p>
<pre><code>const result1 = spinWords( "Привет от Legacy" )
console.log(result1) <span>// тевирП от ycageL</span>

const result2 = spinWords( "This is a test" )
console.log(result2) <span>// This is a test</span></code></pre>
</section>

<!-- ЗАДАНИЕ 4 -->
<section>
<h2>Задание 4*</h2>
<p>
    Напишите функцию, которая принимает массив целых чисел <code>nums</code> и целое число <code>target</code> 
    и возвращает индексы двух чисел так, чтобы они составляли в сумме <code>target</code>.
</p>
<pre><code>Вход: nums = [2,7,11,15], target = 9
Вывод: [0,1]
Объяснение: Поскольку nums[0] + nums[1] == 9, мы возвращаем [0, 1].</code></pre>
</section>

<!-- ЗАДАНИЕ 5 -->
<section>
<h2>Задание 5*</h2>
<p>
    Напишите функцию, которая находит самую длинную строку общего префикса среди массива строк. 
    Если общего префикса нет, то вернуть пустую строку <code>""</code>.<br>
    <em>Префикс - это сочетание из минимум 2-ух букв.</em>
</p>
<pre><code>Ввод: strs = ["цветок","поток","хлопок"]
Вывод: "ок"

Ввод: strs = ["собака","гоночная машина","машина"]
Вывод: ""</code></pre>
</section>

<img width="497" height="453" alt="Снимок экрана 2025-12-19 в 16 36 53" src="https://github.com/user-attachments/assets/c1e97cf9-8490-4c13-aa31-89d4b78ddd49" />
