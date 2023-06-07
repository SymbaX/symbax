name: 不具合報告(Bug report)
description: File a bug report
title: "[BUG]: "
labels: ["🕷️bug"]
body:
  - type: markdown
    attributes:
      value: |
        バグ報告にご協力いただきありがとうございます！
  - type: textarea
    id: what-happened
    attributes:
      label: 概要
      description: 発生したバグについて教えてください
    validations:
      required: true
  - type: markdown
    attributes:
      value: |
        以下、オプションです。よろしければご記入ください。
  - type: textarea
    id: reproduction
    attributes:
      label: 再現手順
      description: バグの再現方法を教えてください
      value: |
        1.
        2.
        3.
    validations:
      required: false
  - type: textarea
    id: expected-behavior
    attributes:
      label: 期待した動作
      description: その機能がするべきはずの動作を教えてください
    validations:
      required: false

  - type: dropdown
    id: browsers
    attributes:
      label: エラーが発生したOSを教えて下さい。
      multiple: true
      options:
        - Android, iOS
        - Androidのみ
        - iOSのみ
        
  - type: textarea
    id: logs
    attributes:
      label: Relevant log output
      description: 関連するログの出力をコピーしてペーストしてください。 これは自動的にコードにフォーマットされるので、バックティックは不要です。
      render: shell
  - type: textarea
    id: other
    attributes:
      label: その他(other)
        
  - type: markdown
    attributes:
      value: |
        この修正を始める際、hotfix/#issue番号_動詞_機能  または　bugfix/#issue番号_動詞_機能 でブランチを切ってください。
