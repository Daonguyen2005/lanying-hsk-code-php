@echo off
echo Starting Lanying HSK Polyglot MVC System...

echo [1/3] Starting Python AI Service on port 8001...
start cmd /k "cd python_ai && pip install -r requirements.txt && python main.py"

echo [2/3] Starting C# Core API on port 5000...
start cmd /k "cd csharp_core && dotnet run"

echo [3/3] Starting PHP View Engine on port 8080...
start cmd /k "cd php_views && php -S localhost:8080 router.php"

echo All services started! 
echo ==============================================
echo 1. PHP Frontend: http://localhost:8080
echo 2. C# Core API: http://localhost:5000
echo 3. Python AI: http://localhost:8001
echo ==============================================
pause
