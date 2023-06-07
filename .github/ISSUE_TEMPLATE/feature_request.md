name: 新規タスク(New Task)
description: 新規タスク用issueを作成する
title: "[Feature]: "
labels: ["🌟feature"]
body:
  - type: textarea
    id: purpose
    attributes:
      label: 目的
      description: このissueの目的を記入してください。
      placeholder:
    validations:
      required: true
  - type: textarea
    id: task_list
    attributes:
      label: タスクリスト
      description: このタスクの達成に必要な作業を具体的に列挙してください。
      value: |
        - [ ] TBD…
    validations:
      required: false
  - type: textarea
    id: reference
    attributes:
      label: 参考
      description: 関連する資料やissueがあれば教えてください。
    validations:
      required: false
  - type: textarea
    id: notice
    attributes:
      label: 留意事項
    validations:
      required: false
  - type: textarea
    id: consideration
    attributes:
      label: 検討事項
    validations:
      required: false
  - type: markdown
    attributes:
      value: |
        この機能の開発を始める際、feature/#issue番号_動詞_機能 でブランチを切ってください。
