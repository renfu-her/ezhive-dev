@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">新增活動</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.activities.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">標題</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="subtitle" class="form-label">副標題</label>
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                    id="subtitle" name="subtitle" value="{{ old('subtitle') }}" required>
                                @error('subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">圖片 (<span class="text-danger">寬度
                                        800px，高度不限</span>)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">日期</label>
                                <input type="text" class="form-control flatpickr @error('date') is-invalid @enderror"
                                    id="date" name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">內容</label>
                                <textarea class="form-control ckeditor @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label">排序</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    id="sort_order" name="sort_order"
                                    value="{{ old('sort_order', $activity->sort_order ?? 0) }}">
                                <small class="form-text text-muted">數字越小排序越前面，留空則自動排到最後</small>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input p-1" id="is_active" name="is_active"
                                    value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                                <label class="form-check-label p-1" for="is_active">啟用廣告</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    創建活動
                                </button>
                                <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
                                    返回列表
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .ck-editor__editable {
            height: 500px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('ckeditor5/zh.min.js') }}"></script>

    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.1/"
            }
        }
    </script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font,
            Alignment,
            Heading,
            List,
            Link,
            Image,
            Table,
            MediaEmbed,
            ImageUpload,
            SimpleUploadAdapter
        } from 'ckeditor5';

        ClassicEditor
            .create(document.querySelector('#content'), {
                language: 'zh',
                simpleUpload: {
                    uploadUrl: '{{ route('admin.upload.image') }}',
                    upload: {
                        types: ['jpeg', 'png', 'gif', 'jpg', 'webp']
                    }
                },
                plugins: [
                    Essentials,
                    Paragraph,
                    Bold,
                    Italic,
                    Font,
                    Alignment,
                    Heading,
                    List,
                    Link,
                    Image,
                    Table,
                    MediaEmbed,
                    ImageUpload,
                    SimpleUploadAdapter
                ],
                toolbar: {
                    items: [
                        'fontSize', 'fontFamily', '|',
                        'fontColor', 'fontBackgroundColor', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'alignment',
                        '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'imageUpload', 'mediaEmbed', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                },
                alignment: {
                    options: ['left', 'center', 'right', 'justify']
                },
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: '段落',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: '標題 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: '標題 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: '標題 3',
                            class: 'ck-heading_heading3'
                        }
                    ]
                },
                fontSize: {
                    options: [
                        8,
                        9,
                        10,
                        11,
                        12,
                        14,
                        16,
                        18,
                        20,
                        22,
                        24,
                        26,
                        28,
                        36,
                        48,
                        72
                    ],
                    supportAllValues: true
                },
                fontFamily: {
                    options: [
                        'default',
                        '微軟正黑體',
                        '新細明體',
                        '標楷體',
                        'Arial',
                        'Times New Roman'
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', '|',
                        'imageStyle:alignLeft',
                        'imageStyle:alignCenter',
                        'imageStyle:alignRight', '|',
                        'imageStyle:full',
                        'imageStyle:side'
                    ],
                    styles: [
                        'full',
                        'side',
                        'alignLeft',
                        'alignCenter',
                        'alignRight'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells',
                        'tableCellProperties',
                        'tableProperties'
                    ]
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        $(document).ready(function() {
            // Flatpickr 初始化
            $('.flatpickr').flatpickr({
                locale: 'zh_tw'
            });
        });
    </script>
@endpush
