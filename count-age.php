<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'title.php';
include 'styles.php';
?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://cdn.pixabay.com/photo/2015/09/19/16/03/space-942693_1280.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 50px auto;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.7);
            animation: fadeIn 2s;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2em;
        }

        input, button {
            padding: 12px;
            margin: 12px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
        }

        input {
            width: calc(100% - 26px);
            background-color: #fff;
            color: #000;
        }

        button {
            background-color: #6a0dad;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 18px;
        }

        button:hover {
            background-color: #4b0082;
        }

        .results {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .result-card {
            position: relative;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #6a0dad;
            padding: 20px;
            border-radius: 15px;
            margin: 15px;
            width: 250px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.7);
            animation: fadeInUp 1s;
            text-align: center;
        }

        .result-card img {
            width: 100%;
            border-radius: 10px;
        }

        .result-card .age {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            font-weight: bold;
            color: #ffd700;
            background: rgba(0, 0, 0, 0.6);
            padding: 5px 10px;
            border-radius: 5px;
            animation: numberIncrease 2s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes numberIncrease {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= e(t('count_age.title')) ?></h1>
        <input type="number" id="ageInput" placeholder="Enter your age (in years)">
        <button onclick="calculateAge()">Calculate Age</button>
        <div id="results" class="results"></div>
    </div>

    <script>
        function calculateAge() {
            const age = parseInt(document.getElementById('ageInput').value);
            const resultsDiv = document.getElementById('results');

            if (isNaN(age) || age <= 0) {
                resultsDiv.innerHTML = "<p>Please enter a valid age.</p>";
                return;
            }

            const planets = {
                Mercury: { yearLength: 0.24, image: 'https://cdn.pixabay.com/photo/2016/06/29/16/30/mercury-1486202_1280.png' },
                Venus: { yearLength: 0.615, image: 'https://cdn.pixabay.com/photo/2016/11/22/07/01/venus-1840476_1280.png' },
                Earth: { yearLength: 1, image: 'https://cdn.pixabay.com/photo/2015/09/18/19/03/earth-944603_1280.png' },
                Mars: { yearLength: 1.88, image: 'https://cdn.pixabay.com/photo/2017/08/30/01/55/mars-2695565_1280.png' },
                Jupiter: { yearLength: 11.86, image: 'https://cdn.pixabay.com/photo/2015/09/05/23/16/jupiter-925756_1280.png' },
                Saturn: { yearLength: 29.46, image: 'https://cdn.pixabay.com/photo/2019/07/16/11/50/saturn-4348151_1280.png' },
                Uranus: { yearLength: 84.01, image: 'https://cdn.pixabay.com/photo/2016/11/18/15/56/uranus-1835517_1280.png' },
                Neptune: { yearLength: 164.8, image: 'https://cdn.pixabay.com/photo/2018/03/01/11/05/neptune-3190448_1280.png' }
            };

            resultsDiv.innerHTML = "";

            for (const [planet, data] of Object.entries(planets)) {
                const ageOnPlanet = (age / data.yearLength).toFixed(2);
                resultsDiv.innerHTML += `
                    <div class="result-card">
                        <img src="${data.image}" alt="${planet}">
                        <div class="age">${ageOnPlanet} years</div>
                        <h3>Your Age on ${planet}</h3>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
