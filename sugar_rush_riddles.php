<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sugar Rush Riddles</title>
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
            <input type="text" id="userInput" class="textField" placeholder="Write your answer here">
            <button id="submitBtn" type="button">Submit Answer</button>
           
        </div>
    </div>

<script>

    const question = document.querySelector('#question');
    const userInput = document.querySelector('#userInput');
    const submitBtn = document.querySelector('#submitBtn');

    const scoreText = document.querySelector('#score');
    const progressText = document.querySelector('#progress'); // Added
    const progressBarFilled = document.querySelector('#progressBarFilled');

    let currentQuestion = {};
    let score = 0;
    let questionCounter = 0;
    let totalQuestions = [];

    const questions = [
        {
            question: 'Eating too much sugar is bad for our ______.',
            answer: ['Teeth', 'Health', 'Body']
        },
        {
            question: 'Choosing food swith less sugar helps us stay ______.',
            answer: 'Healthy'
        },
        {
            question: 'Some people say sugar is not good for us because it makes us gain  ______.',
            answer: ['Weight', 'Fat']
        },
        {
            question: 'Too much sugar can lead to ______ problems like heart disease.',
            answer: 'Health'
        },
        {
            question: 'If we eat too many sweet, we might gain ______.',
            answer: 'Weight'
        }

    ];

    const SCORE_POINTS = 100;
    const MAX_QUESTIONS = 4;

    function startGame() {
        questionCounter = 0;
        score = 0;
        totalQuestions = [...questions];
        getNewQuestion();
    }


    function getNewQuestion() {
    if (totalQuestions.length === 0 || questionCounter >= MAX_QUESTIONS) {
        localStorage.setItem('recentScore', score);
        window.location.assign('sugar_rush_riddles_end.php');
        return;
    }

    questionCounter++;
    progressText.innerText = `Question ${questionCounter} of ${MAX_QUESTIONS}`;
    progressBarFilled.style.width = `${(questionCounter / MAX_QUESTIONS) * 100}%`;

    currentQuestion = totalQuestions.shift(); // Shift the first question from the array
    userInput.value = ''; // Clear previous user input

    question.innerText = currentQuestion.question;
    }

    function handleAnswerSubmission() {
    // Check if userInput element exists
    if (!userInput) {
        console.error("Please type your answer");
        return;
    }
    
    const userAnswer = userInput.value.trim().toLowerCase();
    const correctAnswers = Array.isArray(currentQuestion.answer) ? currentQuestion.answer.map(ans => ans.toLowerCase()) : [currentQuestion.answer.toLowerCase()];

    if (correctAnswers.includes(userAnswer)) {
        score += SCORE_POINTS;
        userInput.style.backgroundColor = '#228B22';
    } else {
        userInput.style.backgroundColor = '#D22B2B';
    }

    setTimeout(() => {
        // Reset background color after a delay
        userInput.style.backgroundColor = '';
        getNewQuestion();
    }, 1050);

    scoreText.innerText = score;
}



    submitBtn.addEventListener('click', handleAnswerSubmission);
    startGame();


</script>
</body>
</html>


</script>
</body>
</html>
