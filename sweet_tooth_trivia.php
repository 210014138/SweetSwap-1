<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Tooth Trivia</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"> 

</head>
<?php
session_start();
include 'config.php';

// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "<script>console.log('Username stored in session: " . $username . "');</script>";

    // Fetch the user's data based on the username
    $stmt = $db->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['Id'];
        $_SESSION['user_id'] = $user_id; // Set the user's ID in session
         // Fetch user's level from the database
         $stmt = $db->prepare("SELECT level FROM user_points WHERE user_id = ?");
         $stmt->execute([$user_id]);
         $user_level = $stmt->fetchColumn();
 
         // Set previous level in session
         $_SESSION['prev_level'] = $user_level;

    } else {
        echo "<p>User not found</p>"; 
   
    }
}

?>
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
        question:'What is sweetness made from?',
        choice1: 'Flour',
        choice2: 'Sugar',
        choice3: 'Salt',
        choice4: 'Pepper',
        answer: 2,
    },
    {
        question: 'Which food tastes sweet?',
        choice1: 'Potato',
        choice2: 'Carrot',
        choice3: 'Broccoli',
        choice4: 'Lollipop',
        answer: 4,
    },
    {
        question:'How does eating too much sweets make our bodies feel?',
        choice1: 'Tired',
        choice2: 'Relaxed',
        choice3: 'Strong',
        choice4: 'Energetic',
        answer: 1,
    },
    {
        question: 'What should we do if we want to eat something sweet?',
        choice1: 'Eat a lot of it',
        choice2: 'Ask our parents for some',
        choice3: 'Choose a healthier option',
        choice4: 'Hide it from our friends',
        answer: 3,
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

            return window.location.assign('sweet_tooth_trivia_end.php')
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
