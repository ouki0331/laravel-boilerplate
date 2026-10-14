#!/bin/bash
set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_DIR"

echo "🔍 [1/3] 检查 SonarQube 服务状态..."
if ! docker ps --format '{{.Names}}' | grep -q "^sonarqube-community$"; then
    if docker ps -a --format '{{.Names}}' | grep -q "^sonarqube-community$"; then
        echo "⏳ 正在唤醒本地 SonarQube 容器..."
        docker start sonarqube-community
    else
        echo "⏳ 正在创建并启动本地 SonarQube 社区版容器..."
        docker run -d --name sonarqube-community \
          -p 9000:9000 \
          -e SONAR_ES_BOOTSTRAP_CHECKS_DISABLE=true \
          sonarqube:community
    fi
fi

echo "⏳ 等待 SonarQube 服务就绪 (通常需要 20-40 秒)..."
until curl -s "http://127.0.0.1:9000/api/system/status" | grep -q '"status":"UP"'; do
    sleep 3
    printf "."
done
echo -e "\n✅ SonarQube 服务已就绪！"

echo "🚀 [2/3] 开始执行代码扫描并推送指标..."
docker run --rm \
  --add-host=host.docker.internal:host-gateway \
  -v "$PROJECT_DIR:/usr/src" \
  sonarsource/sonar-scanner-cli \
  -Dsonar.host.url="http://host.docker.internal:9000" \
  -Dsonar.projectKey=laravel-boilerplate \
  -Dsonar.projectName="Laravel Boilerplate" \
  -Dsonar.sources=app \
  -Dsonar.tests=tests \
  -Dsonar.sourceEncoding=UTF-8

echo "🎉 [3/3] 扫描分析完成！"
echo "👉 请在浏览器打开查看聚合报表: http://localhost:9000/dashboard?id=laravel-boilerplate"
echo "💡 提示：查看完毕后，如果想释放电脑内存，可执行命令停止服务: docker stop sonarqube-community"
