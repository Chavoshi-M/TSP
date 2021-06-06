<!doctype html>
<html> 
    <head>
        <meta charset="utf-8">
        <title>Untitled Document</title>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
    </head>  
    <body>
        <div class="main fixed">
            <form class="form-content card" action="tsp.php" method="post">
                <h1>
                    Traveling Salesman Problem
                </h1> 
                <div class="form-row">
                    <input type="text" name="city" placeholder="City" required />
                </div>
                <div class="form-row">
                    <input type="text" name="npop" placeholder="Npop" required />
                </div>
                <div class="form-row">
                    <input type="text" name="crossover" placeholder="Crossover" required />
                </div>
                <div class="form-row">
                    <input type="text" name="nummutation" placeholder="Nummutation" required />
                </div>
                <div class="form-row">
                    <input type="text" name="iterations" placeholder="Iterations" required />
                </div>
                <div class="form-row"> 
                    <button type="submit" name="submit">RUN</button>
                </div>
            </form> 
        </div> 
    </body> 
</html>