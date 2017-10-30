~!/bin/bash
echo "$(dirname "$0")"
cd "$(dirname "$0")"
7z a archive[$(date +"%d-%m-%y-%H-%M-%S")].7z . -x!*.idea -x!*.git -x!Data -x!Src/Template -x!bin -x!upload