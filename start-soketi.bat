@echo off
echo ===========================================
echo Memulai Soketi Server untuk Expedient
echo ===========================================
set SOKETI_DEFAULT_APP_ID=2153487
set SOKETI_DEFAULT_APP_KEY=15d2104c6127e1b85baa
set SOKETI_DEFAULT_APP_SECRET=8e0776f18c208588b827
npx @soketi/soketi start
