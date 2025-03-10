JSFuckEncoder
=============

JSFuckEncoder is a pure PHP implementation for obfuscating JavaScript code using a methodology inspired by [JSFuck](https://jsfuck.com/). This project encodes any JavaScript code into an obfuscated format that primarily uses the characters `[]()!+` along with constructed expressions, making the original script harder to read.

Installation
------------

You can install this package via Composer by running the following command:

    composer require ildrm/php-jsfuck

Usage
-----

After installation, you can use the package as shown below:

    <?php
    require 'vendor/autoload.php';
    
    use YourVendor\JSFuckEncoder;
    
    $originalScript = 'alert("test");';
    $encodedScript = JSFuckEncoder::encode($originalScript);
    $code = '<script>' . $encodedScript . '</script>';
    ?>
    

Features
--------

*   **Pure PHP Implementation:** No external dependencies like Node.js or npm.
*   **UTF-8 Support:** Handles all UTF-8 characters (including non-English characters) using a combination of pre-defined mappings and fallback methods.
*   **Customizable Mappings:** Leverages base mappings for common strings (e.g., "false", "true", "undefined", "object") and uses `String.fromCharCode` for other characters.
*   **Direct Execution:** Generates output that can be embedded directly within a `<script>` tag in HTML.

Disclaimer
----------

This project is intended for educational and experimental purposes only. While it provides a basic level of obfuscation inspired by JSFuck, it is not guaranteed to be as robust or secure as professional obfuscation tools. **Avoid using this code with untrusted input in production environments** without further security enhancements.

How It Works
------------

**Base Mappings:** The encoder starts with a set of base strings (e.g., "false", "true", "undefined", and "object") to extract common characters by accessing specific indices.

**Fallback Mechanism:** For characters that are not covered by the base mappings, the encoder falls back to using `String.fromCharCode` to generate the appropriate character.

**Self-Executing Code:** The final encoded string is wrapped in a self-executing function via the `([].filter.constructor(...))()` construct, which executes the obfuscated code when the script loads.

Project Structure
-----------------

*   **JSFuckEncoder.php:** Contains the main PHP class with all encoding logic.
*   **README.md:** This file, which provides an overview of the project and instructions on how to use it.

Future Improvements
-------------------

*   **Enhanced Mapping:** Expand the mapping dictionary to cover a broader set of characters, reducing reliance on fallback methods.
*   **Performance Optimization:** Optimize the encoding process for better performance.
*   **Robust Error Handling:** Add more comprehensive error handling for edge cases and invalid input.
*   **Full JSFuck Compliance:** Further refine the output to more closely mimic the complete JSFuck obfuscation style.

Contributing
------------

Contributions are welcome! If you have ideas for improvements or bug fixes, please feel free to fork the repository and submit pull requests.

License
-------

This project is open source and available under the [MIT License](LICENSE).

Happy coding and obfuscating!