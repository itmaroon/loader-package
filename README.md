
# Itmar Loader Package

**WordPress プラグインに最適な、クラス定義を使わない軽量 PSR-4 オートローダー**

このパッケージは `vendor/autoload.php` に依存せず、Composer が生成する `autoload_psr4.php` だけを利用して、  
WordPress 環境下でも **クラス競合なく安全にオートロードと翻訳ファイルの読み込みを実現**します。

---

## 🎯 特徴

- ✅ `spl_autoload_register()` により PSR-4 クラスの自動読み込みを実現
- ✅ `Loader` クラス定義などは一切不要。**グローバル関数も定義しません**
- ✅ `.mo` ファイル（翻訳）も自動でロードされるため、多言語対応も安心
- ✅ 複数プラグインで共存してもクラス・関数の衝突が発生しません

---

## 📦 インストール

```bash
composer require itmar/loader-package
```

---

## 🚀 使用方法（WordPress プラグイン）

### プラグイン構成例

```
your-plugin/
├── plugin.php
├── composer.json
├── vendor/
│   └── itmar/
│       └── loader-package/
│           └── register_autoloader.php
│   └── composer/
│       └── autoload_psr4.php
```

### plugin.php の先頭で呼び出すだけ

```php
require_once __DIR__ . '/vendor/itmar/loader-package/register_autoloader.php';
```

- ✅ PSR-4 にマッピングされたクラスが自動で読み込まれます
- ✅ 対応する `.mo` 翻訳ファイルも自動ロードされます

---

## 🌐 翻訳ファイルの自動ロード

各 PSR-4 名前空間ごとに自動で以下を探します：

- パス：`{パッケージ}/languages/`
- ファイル名：`{textdomain}-{locale}.mo`（例：`my-plugin-ja.mo`）
- テキストドメインは名前空間から自動生成されます

### 例

```
vendor/itmar/block-class-package/languages/block-class-package-ja.mo
```

名前空間が `Itmar\BlockClassPackage\` の場合、テキストドメインは `block-class-package` になります。

---

## ⚙ 技術的仕様

- `debug_backtrace()` で呼び出し元から `vendor/composer/autoload_psr4.php` を特定
- すべての PSR-4 マッピングに対して `spl_autoload_register()` を登録
- `.mo` ファイルを `load_textdomain()` で即時読み込み（WordPress の i18n対応）

---

## ✅ なぜ `vendor/autoload.php` を使わないのか？

WordPress では複数のプラグインが独自に Composer を使うことが多く、  
`vendor/autoload.php` を読み込むと `ComposerAutoloaderInitXXXX` クラスの競合が発生し、  
**`Fatal error` によってサイト全体が停止することがあります。**

このパッケージは `autoload_psr4.php` のみを使用することで、**安全かつ衝突のないクラスローディングを可能にします。**

---

## 📄 ライセンス

MIT License

---

## 👤 作者

**Itmaroon**  
<master@itmaroon.net>  
https://itmaroon.net
