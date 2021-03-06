XP Compiler ChangeLog
=====================

## ?.?.? / ????-??-??

## 5.6.0 / 2020-11-22

* Optimized null-safe instance operator for PHP 8.0 - @thekid
* Added PHP 8.1-dev to test matrix now that is has been branched
  (@thekid)
* Added support for non-capturing catches, see this PHP 8 RFC:
  https://wiki.php.net/rfc/non-capturing_catches
  (@thekid)

## 5.5.0 / 2020-11-15

* Merged PR #91 - Refactor rewriting type literals:
  - Changed implementation to be easier to maintain
  - Emit function types as `callable` in all PHP versions
  - Emit union types as syntax in PHP 8+
  (@thekid)

## 5.4.1 / 2020-10-09

* Fixed #90: Namespace declaration statement has to be the very first 
  statement, which occured with PHP 8.0.0RC1
  (@thekid)

## 5.4.0 / 2020-09-12

* Implemented second step for #86: Add an E_DEPRECATED warning to the
  hacklang annotation syntax `<<...>>`; details in xp-framework/ast#9
  (@thekid)
* Merged PR #89: Add annotation type mappings to `TARGET_ANNO` detail
  (@thekid)
* Changed PHP 8 attributes to be emitted in XP meta information without
  namespaces, and with their first characters lowercased. This way, code
  using annotations will continue to work, see xp-framework/rfc#336.
  (@thekid)

## 5.3.0 / 2020-09-12

* Merged PR #88: Emit named arguments for PHP 8 - @thekid

## 5.2.1 / 2020-09-09

* Adjusted to `xp-framework/ast` yielding comments as-is, transform
  them to the form XP meta information expects.
  (@thekid)

## 5.2.0 / 2020-07-20

* Merged PR #87: Add support for match expression - @thekid
* Implemented first step of #86: Support PHP 8 attributes - @thekid
* Removed `lang.ast.syntax.php.NullSafe` in favor of builtin support
  (@thekid)
* Merged PR #84: Extract parser - @thekid

## 5.1.3 / 2020-04-04

* Allowed `::class` on objects (PHP 8.0 forward compatibility) - @thekid

## 5.1.2 / 2020-04-04

* Fixed promotion for by-reference arguments - @thekid

## 5.1.1 / 2020-03-29

* Fixed ternary and instanceof operators' precedence - @thekid

## 5.1.0 / 2020-03-28

* Merged PR #82: Allow chaining scope resolution operator `::` - @thekid
* Merged PR #81: Allow `instanceof (<expr>)` as syntax - @thekid
* Merged PR #80: Allow `new (<expr>)` as syntax - @thekid

## 5.0.0 / 2019-11-30

* Merged PR #70: Extract compact methods; to use these, require the
  library https://github.com/xp-lang/php-compact-methods
  (@thekid)
* Merged PR #79: Convert testsuite to baseless tests - @thekid
* Merged PR #78: Deprecate curly brace syntax for offsets; consistent
  with PHP 7.4
  (@thekid)
* Added support for XP 10 and newer versions of library dependencies
  (@thekid)
* Implemented xp-framework/rfc#334: Drop PHP 5.6. The minimum required
  PHP version is now 7.0.0!
  (@thekid)

## 4.3.1 / 2019-11-30

* Added compatibility with XP 10, see xp-framework/rfc#333 - @thekid

## 4.3.0 / 2019-11-24

* Fixed global constants in ternaries being ambiguous with goto labels
  (@thekid)
* Fixed emitting `switch` statements and case labels' ambiguity w/ goto
  (@thekid)
* Fixed an operator precedence problem causing incorrect nesting in the
  parsed AST for unary prefix operators.
  (@thekid)
* Merged PR #77: Add support for #-style comments including support for
  XP style annotations
  (@thekid)

## 4.2.1 / 2019-10-05

* Fixed parser to allow "extending" final and abstract types - @thekid

## 4.2.0 / 2019-10-04

* Fixed issue #74: No longer shadow compiler errors in certain cases
  (@thekid)
* Merged PR #75: Add "ast" subcommand to display the abstract syntax tree
  (@thekid)

## 4.1.0 / 2019-10-01

* Merged PR #73: Add support for annotations in anonymous classes
  (@thekid)

## 4.0.0 / 2019-09-09

* Merged PR #69: Remove support for Hack arrow functions - @thekid
* Fixed operator precedence for unary prefix operators - @thekid
* Merged PR #66: Syntax plugins. With this facility in place, the compiler
  can be extended much like [Babel](https://babeljs.io/docs/en/plugins).
  This is useful for adapting features which may or may not make it into
  PHP one day. Current extensions like compact methods are kept for BC
  reasons, but will be extracted into their own libraries in the future!
  (@thekid)

## 3.0.0 / 2019-08-10

* Made compatible with PHP 7.4 - refrain using `{}` for string offsets
  (@thekid)
* Merged PR #45 - Multiple errors - @thekid
* Changed compiler to emit deprecation warnings for Hack language style
  arrow functions and compact methods using `==>`, instead advocating the
  use of PHP 7.4 with the `fn` keyword; see issue #65
  (@thekid)

## 2.13.0 / 2019-06-15

* Added preliminary PHP 8 support - see #62 (@thekid)
* Added [support for PHP 7.4 features](https://github.com/xp-framework/compiler/projects/2)
  - Implemented numeric literal separator, e.g. `1_000_000_000` - see #61
  - Implemented null-colaesce assignment operator `??=` - see #58
  - Implemented support PHP 7.4-style short closures with `fn` - see #60
  - Implemented support for emitting typed properties in PHP 7.4 - see #57
  (@thekid)

## 2.12.0 / 2018-12-28

* Added support for [PHP 7.3](http://php.net/archive/2018.php#id2018-12-06-1)
  (@thekid)

## 2.11.1 / 2018-09-14

* Fixed #56: Resolved paths not absolute - @thekid

## 2.11.0 / 2018-08-11

* Merged PR #54: Ensure filenames in errors reflect source files - @thekid

## 2.10.1 / 2018-06-24

* Fixed throw expressions with variables, e.g. `() ==> throw $e;`.
  (@thekid)
* Fixed URI loading for CompilingClassLoader - this way, loading
  classes via URIs works, and thus e.g. `xp test path/to/Test.php`.
  (@thekid)

## 2.10.0 / 2018-06-21

* Merged PR #53: Implement throw expressions - @thekid

## 2.9.0 / 2018-06-19

* Merged PR #52: Implement "goto" statement - @thekid
* Merged PR #50: Allow arrow functions with blocks - @thekid

## 2.8.0 / 2018-06-17

* Merged PR #47: Allow empty catch type to catch all exceptions - @thekid
* Merged PR #44: Implement `echo` statement - @thekid

## 2.7.0 / 2018-06-16

* Merged PR #43: Add "-n" command line option to compile only - @thekid
* Merged PR #42: Raise errors when members are redeclared - @thekid

## 2.6.2 / 2018-06-16

* Fixed issue with dynamic instance references not being emitted
  correctly, e.g. `$value->{$field->get()};`, see
  http://php.net/manual/en/functions.variable-functions.php
  (@thekid)

## 2.6.1 / 2018-06-10

* Fixed issue #41: String parsing of escapes broken - @thekid

## 2.6.0 / 2018-06-10

* Implemented support unicode escape sequences in PHP 5.6, see #38
  (@thekid)
* Fixed issue #39: Syntax error for parameters called "function" 
  (@thekid)
* Dropped confusing way of compiling multiple sources using `-b` to
  strip bases. New way is to pass multiple directories directly, e.g.
  `$ xp compile -o dist src/main/php/ src/test/php`
  (@thekid)
* Fixed compiling to a directory when the source path was not inside
  the current directory.
  (@thekid)

## 2.5.1 / 2018-06-10

* Ensured line number is always present for type members. Previously,
  this was 0, leading to output formatting errors
  (@thekid)
* Made some minor performance improvements by reusing nodes in two
  cases - return statements and assignments
  (@thekid)

## 2.5.0 / 2018-06-09

* Implemented feature request #9: Support null-safe instance operator
  (@thekid)

## 2.4.0 / 2018-06-08

* Added context to various parse errors. Now messages read something
  like `Expected ",", have "(end)"" in parameter list`.
  (@thekid)
* Improved error messages: Include file name (w/o full path) and line
  number in exceptions raised from class loading.
  (@thekid)

## 2.3.0 / 2018-04-02

* Merged PR #33: Using statement - @thekid

## 2.2.0 / 2018-03-30

* Fixed typed properties inside comma-separated listing, for example:
  `private string $a, int $b`
  (@thekid)
* Implemented support for typed class constants `const int T = 5`
  (@thekid)

## 2.1.0 / 2018-03-29

* Implemented support for `mixed` type, see issue #28 - @thekid
* Fixed issue #32: Test suite failure on HHVM 3.25 - @thekid
* Allowed trailing commas in grouped use lists as implemented in
  https://wiki.php.net/rfc/list-syntax-trailing-commas
  (@thekid)
* Fixed nullable value types being emitted incorrectly - @thekid
* Merged PR #30: Implement compiling to directory. The command line
  `xp compile src/main/php dist/` will compile all source files inside
  the `src/main/php` directory to `dist`.
  (@thekid)

## 2.0.5 / 2018-02-25

* Fixed apidoc comments for methods, traits and interfaces - @thekid

## 2.0.4 / 2017-11-19

* Fixed issue #27: Class not found - @thekid

## 2.0.3 / 2017-11-16

* Fixed cast on array and map literals, e.g. `(object)['key' => 'value']`
  (@thekid)

## 2.0.2 / 2017-11-14

* Fixed issue #25: Warnings for `return;` - @thekid

## 2.0.1 / 2017-11-06

* Fixed issue #24: Comments contain stars - @thekid

## 2.0.0 / 2017-11-06

* Implemented `use function` and `use const` - @thekid
* Fixed issue #21: Comments are not escaped - @thekid
* Project [AST API](https://github.com/xp-framework/compiler/projects/1):
  - Merged PR #22: Extract AST (to https://github.com/xp-framework/ast)
  - Index annotations by name
  - Split `new` for static and anonymous types
  - Simplified parsing and emitting loops and if/else constructs
  - Renamed `Node::$arity` to `Node::$kind`
  - Merged PR #20: Refactor signature
  - Merged PR #19: Refactor value arrays to specialized types
  (@thekid)

## 1.4.0 / 2017-11-04

* Merged PR #18: Allow using unpack operator inside array literals
  (@thekid)
* Added option to specify target version to `xp compile` - @thekid
* Fixed isse #17: Comments missing from generated code - @thekid

## 1.3.0 / 2017-11-04

* Made it possible to use `<?hh` as opening tag, too. This way, we are
  able to parse Hack language files.
  (@thekid)

## 1.2.1 / 2017-10-31

* Changed ambiguity resolution between casts, braced expressions and
  lambda to be far more robust
  (@thekid)

## 1.2.0 / 2017-10-31

* Added support for import aliases (`use Type as Alias`) - @thekid
* Fixed issue #16: Enums broken - @thekid

## 1.1.2 / 2017-10-31

* Fixed issue #15: Interop with xp-forge/partial broken - @thekid

## 1.1.1 / 2017-10-31

* Fixed map initialization with keys consisting of complex expressions
  (@thekid)

## 1.1.0 / 2017-10-31

* Implemented trait usage, including aliasing via `as`. See issue #14
  (@thekid)

## 1.0.0 / 2017-10-25

* Indexed type members by name; implementing feature suggested in #10 
  (@thekid)
* **Heads up:** Implemented syntax for parameter annotations as stated 
  in issue #1 - alongside the parameter; no longer in its "targeted" form
  `$param: inject` as in https://github.com/xp-framework/rfc/issues/218
  (@thekid)
* Added support for keywords as methods in PHP 5.6 - @thekid
* Implemented xp-framework/rfc#326: Cast and nullable types - @thekid
* Added support for casting value and array types - @thekid

## 0.9.1 / 2017-10-21

* Fixed promoted argument types not being recorded - @thekid

## 0.9.0 / 2017-10-21

* Added support for `$arg ==> $arg++` lambdas without argument braces
  (@thekid)
* Fixed issue #8: Member types missing for constructor argument promotion
  (@thekid)
* Fixed issue #7: Ternary operator broken - @thekid
* Fixed issue #6: instanceof does not resolve class names - @thekid
* Implemented support for union types, e.g. `int|float`, as supported
  by [this PHP RFC](https://wiki.php.net/rfc/union_types)
  (@thekid)
* Implemented `array<int>` and `array<string, string>` as well as
  function types (e.g. `(function(int, string): string)`) as seen in
  [Hack's type system](https://docs.hhvm.com/hack/types/type-system)
  (@thekid)

## 0.8.0 / 2017-10-18

* Ensured line numbers are kept; this is important for tracing
  warnings, errors and exceptions.
  (@thekid)

## 0.7.0 / 2017-10-15

* Improved function, closure, lambda and method node layouts - @thekid
* Fixed closures not being able to use by reference - @thekid
* Implemented parameter annotations via `$param: inject` - @thekid

## 0.6.0 / 2017-10-15

* Ensured types are checked where natively supported - @thekid
* Implemented capturing locals in lambda expressions - @thekid
* Recorded property types in cached meta data - @thekid
* Implemented support for dynamic new via `new $type`- @thekid
* Fixed assignment operator - @thekid
* Fixed parameter types - @thekid
* Fixed annotations not having access to class scope - @thekid
* Fixed constant emittance in PHP 7.1+ - @thekid
* Fixed trait declaration - @thekid
* Fixed issue #3: Annotations in package - @thekid
* Fixed issue #4: "xp compile" installation - @thekid

## 0.5.0 / 2017-10-15

* Removed unused scope defines - @thekid
* Fixed endless loop for unclosed argument lists - @thekid
* Fixed type annotations not being parsed - @thekid

## 0.4.0 / 2017-10-15

* Optimized runtime performance by including annotations as 
  metadata inside code, see issue #1.
  (@thekid)
* Fixed annotation parsing - @thekid
* Made `xp help compile` display something useful - @thekid
* Fixed compatibility with XP7 console streaming - @thekid

## 0.3.0 / 2017-10-15

* Registered `xp compile` subcommand - @thekid
* Simulated `yield from` in PHP 5.6 in a limited fashion - @thekid
* Added support for nullable types from PHP 7.1 - @thekid
* Implemented short `list(...)` syntax from PHP 7.1 - @thekid
* Added support for anonymous classes from PHP 7.0 - @thekid
* Implemented constant modifiers from PHP 7.1 - @thekid
* Added support for comparison operator `<=>` from PHP 7.0 - @thekid
* Added support for `object` typehint from PHP 7.2 - @thekid

## 0.2.0 / 2017-10-14

* Added factory to retrieve emitter for a given PHP runtime - @thekid
* Extracted PHP version specific handling to dedicated classes - @thekid
* Ensured compiled code can be loaded for annotation parsing - @thekid

## 0.1.0 / 2017-10-14

* First public release - @thekid
