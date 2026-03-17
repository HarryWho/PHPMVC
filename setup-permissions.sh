#!/bin/bash
# Setup script for PHP MVC project permissions

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "Setting up PHP MVC project permissions..."

# Set logs directory permissions
if [ -d "$PROJECT_ROOT/logs" ]; then
    chmod -R 777 "$PROJECT_ROOT/logs"
    echo "✓ logs/ directory permissions set"
fi

# Set public directory permissions
if [ -d "$PROJECT_ROOT/public" ]; then
    chmod -R 755 "$PROJECT_ROOT/public"
    echo "✓ public/ directory permissions set"
fi

# Set app directory permissions
if [ -d "$PROJECT_ROOT/app" ]; then
    chmod -R 755 "$PROJECT_ROOT/app"
    echo "✓ app/ directory permissions set"
fi

# For production, you might want to set specific web server user:
# Uncomment and modify WEB_USER if needed (common values: www-data, apache, _www)
# WEB_USER="www-data"
# sudo chown -R $WEB_USER:$WEB_USER "$PROJECT_ROOT/logs"
# sudo chmod -R 775 "$PROJECT_ROOT/logs"

echo "Setup complete!"
echo ""
echo "Note: Make sure your web server has write access to:"
echo "  - $PROJECT_ROOT/logs"
echo ""
echo "If using nginx/apache with specific user, you may need to run:"
echo "  sudo chown -R www-data:www-data ./logs"
echo "  sudo chmod -R 775 ./logs"
