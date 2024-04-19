<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweetness Showdown</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="quiz-new-container">
        <div id="game" class="flex-column flex-center">
          <div id="top">
            <div class="top-section">
                <p id="progress" class="progressText">
                    Question
                </p>
                <div id="progressBar">
                    <div id="progressBarFilled"></div>
                </div>
            </div>
            <div id="top-section">
                <p id="progressText">
                    Score
                </p>
                <h1 class="scoreNumber" id="score">
                    0
                </h1>
            </div>
          </div>
          <h1 id="question">What is the answer to this question?</h1>
          <div class="choices">
            <p class="choice-letter">A</p>
            <p class="choiceText" data-number="1">Choice 1</p>
          </div>
          <div class="choices">
            <p class="choice-letter">B</p>
            <p class="choiceText" data-number="2">Choice 2</p>
          </div>
          <div class="choices">
            <p class="choice-letter">C</p>
            <p class="choiceText" data-number="3">Choice 3</p>
          </div>
          <div class="choices">
            <p class="choice-letter">D</p>
            <p class="choiceText" data-number="4">Choice 4</p>
          </div>
        </div>
    </div>
    <script>

const question = document.querySelector('#question');
const choices = Array.from(document.querySelectorAll('.choiceText'));
const progressText = document.querySelector('#progress');
const scoreText = document.querySelector('#score');
const progressBarFilled = document.querySelector('#progressBarFilled');
  
let currentQuestion= {}
let markingAnswers = true
let score = 0
let questionCounter = 0
let totalQuestions = []

let questions = [
    {
        question: 'What one is the healthier option?',
        choice1: 'Fruits',
        choice2: 'Chocolate bars',
        choice3: 'Crisps',
        choice4: 'Ice cream',
        answer: 1,
    },
    {
        question: 'What can too much sugar do to our teeth?',
        choice1: 'Make them stronger',
        choice2: 'Make them shine',
        choice3: 'Make them fall out',
        choice4: 'Make them whiter',
        answer: 3,
    },
    {
        question: 'Which one is bad for you?',
        choice1: 'Fruit and vegetables',
        choice2: 'Oats',
        choice3: 'Chocolate',
        choice4: 'Meat',
        answer: 3,
    },
    {
        question: 'How can you still enjoy sweet foods?',
        choice1: 'Have smaller portions',
        choice2: 'Choose sugar-free options',
        choice3: 'Eat fruit instead of sweets',
        choice4: 'All of the above',
        answer: 4,
    }
]

const SCORE_POINTS = 100 // if you get a question correct you get a score of 100 points
const MAX_QUESTIONS = 4 //this means that there is a fixed number of questions

startGame = () => {
    questionCounter = 0
    score = 0
    totalQuestions = [...questions]
    getNewQuestion()


    
}

getNewQuestion = () => {
    if (totalQuestions.length === 0 || questionCounter > MAX_QUESTIONS){
        localStorage.setItem('recentScore', score)

        return window.location.assign('sweetness_showdown_end.php')
    }

    questionCounter++
    progressText.innerText = `Question ${questionCounter} of ${MAX_QUESTIONS}`
    progressBarFilled.style.width = `${(questionCounter/MAX_QUESTIONS) * 100}%`

    const questionIndex = Math.floor(Math.random() * totalQuestions.length)
    currentQuestion = totalQuestions[questionIndex]
    question.innerText = currentQuestion.question

    choices.forEach(choice => {
        const number = choice.dataset['number']
        choice.innerText = currentQuestion['choice' + number]

    })

    totalQuestions.splice(questionIndex, 1)

    markingAnswers = true


}

choices.forEach(choice => {
    choice.addEventListener('click', e => {
       
        if(!markingAnswers) return

        markingAnswers = false
        const selectedChoice = e.target
        const selectedAnswer = selectedChoice.dataset['number']

        // Determine if the selected answer is correct
        let isCorrect = selectedAnswer == currentQuestion.answer;

        // Apply the appropriate background color based on correctness to the choice container
        selectedChoice.parentElement.style.backgroundColor = isCorrect ? '#228B22' : '#D22B2B';

        if(isCorrect) {
            incrementScore(SCORE_POINTS);
        }

        setTimeout(() => {
            // Reset background color after a delay
            selectedChoice.parentElement.style.backgroundColor = '';
            getNewQuestion();
        }, 1000);
    });
});



incrementScore = num => {
    score +=num
    scoreText.innerText = score
}



startGame()
</script>
</body>
</html>