@echo off
title Lab Exam Q6: Full-Stack REST API Server
echo ============================================================
echo   STARTING LAB EXAM Q6: FULL-STACK REST API SERVER
echo   Syllabus Coverage: Experiments 1 to 10 (Syllabus 76 to 85)
echo ============================================================
echo.
echo Launching REST API on http://localhost:3001
echo Opening exam6_fullstack_crud.html in default browser...
start "" "%~dp0exam6_fullstack_crud.html"
node "%~dp0fullstack_server.js"
pause

