# Itmar Loader Package（イツマール・ローダー・パッケージ）

**WordPress プラグインやライブラリ開発に最適な、軽量・安全な PSR-4 オートローダーパッケージ**です。

このパッケージは、Composer の `vendor/autoload.php` に依存せず、`autoload_psr4.php` だけを使って **PSR-4 に準拠したクラスを安全に読み込む**ことができます。

---

## 🎯 このパッケージの特徴

- ✔️ `vendor/autoload.php` を使いません  
- ✔️ WordPress プラグイン間でのクラス競合（`ComposerAutoloaderInitXXX`）を回避できます  
- ✔️ たった 1 行の `require_once` でクラスオートローダーが有効化されます  
- ✔️ PSR-4 クラスの読み込みと `.mo` 翻訳ファイルの読み込みが自動で行われます

---

## 📦 インストール方法

```bash
composer require itmaroon/loader-package
```

`vendor/itmar/loader-package/` にパッケージがインストールされます。

---

## 🚀 プラグインでの利用手順

### プラグイン構成例

```
your-plugin/
├── plugin.php
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

### プラグインのエントリーポイントでたった1行

```php
require_once __DIR__ . '/vendor/itmar/loader-package/src/register_autoloader.php';
```

これだけで：

- ✅ クラスオートローダーが有効化
- ✅ 翻訳ファイルも自動で読み込まれます

---

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

## 🌐 国際化（翻訳ファイル）の自動読み込み

このパッケージは、**PSR-4 マッピングに基づいて各パッケージの翻訳ファイル（`.mo`）を自動読み込みする機能**を備えています。  
利用者は `textdomain` を手動で指定したり、`load_plugin_textdomain()` を呼び出す必要はありません。

### 翻訳ファイルの設置ルール

```
vendor/
└── itmar/
    └── block-class-package/
          └── languages/
              └── block-class-package-ja.mo
```

| 項目             | 値例                             | 備考                          |
|------------------|----------------------------------|-------------------------------|
| テキストドメイン | `block-class-package`            | 名前空間から自動推測されます |
| ファイル名       | `block-class-package-ja.mo`      | `get_locale()` に準拠        |
| 配置パス         | `assets/languages/`              | パッケージルート基準         |

### 自動読み込みの仕組み

- `autoload_psr4.php` のすべての名前空間を走査
- それぞれの `assets/languages/` に対応する `.mo` を探して自動で読み込む

### テキストドメインの命名規則

| 名前空間                        | テキストドメイン |
|--------------------------------|------------------|
| `Itmar\BlockClassPackage\`   | `block-class-package` |
| `Vendor\AwesomePlugin\`      | `awesome-plugin` |

---

## 🔍 技術的なポイント

- `Loader.php` が `autoload_psr4.php` を走査し、`spl_autoload_register()` を登録
- 各名前空間に基づいて `.mo` を `load_plugin_textdomain()` で自動読み込み
- `autoload_classmap.php` や `autoload_static.php` は使用しません

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

- [`itmar/block-class-package`](https://packagist.org/packages/itmar/block-class-package)  
  Gutenberg ブロック関連の共通ユーティリティ。クラス・翻訳対応済み。

---

## 💡 補足：複数プラグイン間での共通利用にも対応

このパッケージは `mu-plugins/` 等に一括で導入して、**全プラグイン共通のローダーとして使うことも可能**です。  
複数のパッケージ・プラグインでの競合を防ぎつつ、安全に共通ロジックを管理できます。
