#!/bin/bash

echo "==================================="
echo "Plugin Structure Verification"
echo "==================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $1 exists"
        return 0
    else
        echo -e "${RED}✗${NC} $1 missing"
        return 1
    fi
}

check_syntax() {
    if php -l "$1" > /dev/null 2>&1; then
        echo -e "${GREEN}✓${NC} $1 has valid syntax"
        return 0
    else
        echo -e "${RED}✗${NC} $1 has syntax errors"
        return 1
    fi
}

echo "Checking file structure..."
echo ""

# Check main files
check_file "dolibarr-contact-form.php"
check_file ".gitignore"
check_file "README.md"
check_file "USAGE.md"

echo ""
echo "Checking includes directory..."
check_file "includes/class-dolibarr-api.php"
check_file "includes/class-form-handler.php"
check_file "includes/class-logger.php"

echo ""
echo "Checking templates directory..."
check_file "templates/contact-form.php"

echo ""
echo "Checking assets directories..."
check_file "assets/css/style.css"
check_file "assets/js/script.js"

echo ""
echo "==================================="
echo "PHP Syntax Validation"
echo "==================================="
echo ""

check_syntax "dolibarr-contact-form.php"
check_syntax "includes/class-dolibarr-api.php"
check_syntax "includes/class-form-handler.php"
check_syntax "includes/class-logger.php"
check_syntax "templates/contact-form.php"

echo ""
echo "==================================="
echo "Code Quality Checks"
echo "==================================="
echo ""

# Check for security issues
echo "Checking for direct access protection..."
if grep -q "if (!defined('ABSPATH'))" dolibarr-contact-form.php; then
    echo -e "${GREEN}✓${NC} Main file has ABSPATH check"
else
    echo -e "${RED}✗${NC} Main file missing ABSPATH check"
fi

if grep -q "if (!defined('ABSPATH'))" includes/*.php; then
    echo -e "${GREEN}✓${NC} Include files have ABSPATH checks"
else
    echo -e "${RED}✗${NC} Some include files missing ABSPATH checks"
fi

# Check for sanitization
echo ""
echo "Checking for input sanitization..."
if grep -q "sanitize_text_field\|sanitize_email\|sanitize_textarea_field" includes/*.php; then
    echo -e "${GREEN}✓${NC} Sanitization functions found"
else
    echo -e "${RED}✗${NC} No sanitization functions found"
fi

# Check for nonce verification
echo ""
echo "Checking for nonce verification..."
if grep -q "check_ajax_referer" dolibarr-contact-form.php; then
    echo -e "${GREEN}✓${NC} Nonce verification found"
else
    echo -e "${RED}✗${NC} No nonce verification found"
fi

echo ""
echo "==================================="
echo "Configuration Checks"
echo "==================================="
echo ""

# Check API configuration
if grep -q "5P3cw77r825RIXwE8eGuZIj4dmcPF0kK" includes/class-dolibarr-api.php; then
    echo -e "${GREEN}✓${NC} API Key configured"
else
    echo -e "${RED}✗${NC} API Key not found"
fi

if grep -q "https://intetron.co/plataforma/api/index.php" includes/class-dolibarr-api.php; then
    echo -e "${GREEN}✓${NC} API Base URL configured"
else
    echo -e "${RED}✗${NC} API Base URL not found"
fi

echo ""
echo "==================================="
echo "Verification Complete!"
echo "==================================="
