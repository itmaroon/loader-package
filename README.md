# Itmar Loader Package（イツマール・ローダー・パッケージ）

**WordPress プラグインやライブラリ開発に最適な、軽量・安全な PSR-4 オートローダーパッケージ**です。

このパッケージは、Composer の `vendor/autoload.php` に依存せず、`autoload_psr4.php` だけを使って **PSR-4 に準拠したクラスを安全に読み込む**ことができます。

---

## 🎯 このパッケージの特徴

- ✔️ **`vendor/autoload.php` を使いません**  
- ✔️ **WordPress プラグイン間でのクラス競合（`ComposerAutoloaderInitXXX`）を回避できます**
- ✔️ たった **1 行の `require_once` でクラスオートローダーが有効化**されます
- ✔️ Composer の **`autoload_psr4.php` のみ**を使ってオートロードするため、動作が明確で安全です

---

## 📦 インストール方法

```bash
composer require itmaroon/loader-package
```

`vendor/itmar/loader-package/` にパッケージがインストールされます。

---

## 🚀 プラグインでの利用手順（導入事例）

### 1. WordPress プラグイン構成例

```
your-plugin/
├── plugin.php                      ← プラグインのエントリポイント
├── composer.json
├── src/
│   └── YourClass.php
├── vendor/
│   ├── itmar/
│   │   └── loader-package/
│   │       └── src/
│   │           ├── Loader.php
│   │           └── register_autoloader.php
│   └── composer/
│       └── autoload_psr4.php
```

### 2. `plugin.php` に次の1行を追加

```php
require_once __DIR__ . '/vendor/itmar/loader-package/src/register_autoloader.php';
```

### 3. クラスを普通に使うだけ！

```php
use YourPluginNamespace\YourClass;

YourClass::do_something();
```

---

## ✅ なぜ `vendor/autoload.php` を使わないのか？

WordPress では、複数のプラグインがそれぞれ Composer を使っていると、

- `ComposerAutoloaderInitXXXX` という自動生成クラスが重複して
- クラス名の競合や `Fatal error` を引き起こす

といった問題が発生します。

このパッケージは `autoload_psr4.php` のみを使ってローディングを行うため、**クラス競合のない、安全なオートロード構成**が実現できます。

---

## 🔍 技術的なポイント

- `register_autoloader.php` が `Loader.php` を呼び出し、`spl_autoload_register()` を登録します
- `autoload_psr4.php` に定義された PSR-4 マップに従って、クラスを自動的に読み込みます
- `autoload_classmap.php` や `autoload_static.php` は一切使用しません

---

## 📄 ライセンス

MIT License

---

## 👤 作者情報

**Itmaroon**  
<master@itmaroon.net>  
https://itmaroon.net

---

## 🙌 関連パッケージ

このオートローダーと連携して利用できるパッケージ例：

- [`itmar/block-class-package`](https://packagist.org/packages/itmar/block-class-package)  
  Gutenberg ブロック関連の共通ユーティリティパッケージ。  
  オートローダーを使ってクラスを自動読み込み可能。

---

## 💡 補足：複数プラグイン間での共通利用にも対応

このパッケージは、`mu-plugins` などで **一度だけ `require_once` しておく構成**にも対応可能です。  
全プラグインで共通オートローダーとして使用したい場合は、`itmar-core` などのコアプラグインに設置するのがおすすめです。

---
