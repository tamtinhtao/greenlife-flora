@extends('layouts.admin')


@section(
    'title',
    'Chat khách hàng - GreenLife Admin'
)


@section(
    'page-title',
    'Chat khách hàng'
)


@section(
    'page-subtitle',
    'Trao đổi và hỗ trợ khách hàng'
)



@push('styles')

<style>

    /* =====================================================
       PAGE
    ===================================================== */

    .chat-page-heading {

        margin-bottom: 22px;
    }


    .chat-page-title {

        margin: 0 0 5px;

        font-size: 27px;

        font-weight: 500;
    }


    .chat-page-description {

        margin: 0;

        color: #8997a4;
    }



    /* =====================================================
       MAIN CHAT
    ===================================================== */

    .admin-chat-page {

        height:
            calc(
                100vh - 205px
            );

        min-height: 620px;

        display: grid;

        grid-template-columns:
            300px 1fr;

        overflow: hidden;

        background: white;

        border:
            1px solid #dfe4e8;

        box-shadow:
            0 1px 5px
            rgba(
                0,
                0,
                0,
                .07
            );
    }



    /* =====================================================
       LEFT - USER LIST
    ===================================================== */

    .chat-users-panel {

        min-width: 0;

        display: flex;

        flex-direction: column;

        background: #f7f9fa;

        border-right:
            1px solid #dfe4e8;
    }


    .chat-users-header {

        min-height: 70px;

        padding:
            16px 18px;

        display: flex;

        flex-direction: column;

        justify-content: center;

        border-bottom:
            1px solid #dfe4e8;

        background: white;
    }


    .chat-users-title {

        font-size: 16px;

        font-weight: 700;
    }


    .chat-users-subtitle {

        margin-top: 3px;

        color: #87939d;

        font-size: 11px;
    }



    /* SEARCH */

    .chat-search-box {

        padding: 12px;

        border-bottom:
            1px solid #e3e7ea;

        background: #f7f9fa;
    }


    .chat-search-input {

        font-size: 13px;
    }



    /* USER LIST */

    .chat-user-list {

        flex: 1;

        overflow-y: auto;
    }


    .chat-user-item {

        width: 100%;

        display: flex;

        align-items: center;

        gap: 11px;

        padding:
            13px 15px;

        border: 0;

        border-bottom:
            1px solid #e6eaed;

        background: transparent;

        text-align: left;

        cursor: pointer;

        transition: .15s;
    }


    .chat-user-item:hover {

        background: #eaf3f8;
    }


    .chat-user-item.active {

        background: #dceef8;

        border-left:
            4px solid #3898c4;

        padding-left: 11px;
    }


    .chat-user-avatar {

        width: 42px;
        height: 42px;

        flex: 0 0 42px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        background: #3898c4;

        color: white;

        font-size: 15px;

        font-weight: 700;
    }


    .chat-user-info {

        min-width: 0;

        flex: 1;
    }


    .chat-user-name-row {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 5px;
    }


    .chat-user-name {

        overflow: hidden;

        font-size: 13px;

        font-weight: 700;

        text-overflow: ellipsis;

        white-space: nowrap;
    }


    .chat-user-email {

        margin-top: 4px;

        overflow: hidden;

        color: #7d8993;

        font-size: 11px;

        text-overflow: ellipsis;

        white-space: nowrap;
    }


    .chat-unread-badge {

        min-width: 21px;
        height: 21px;

        padding: 0 6px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 11px;

        background: #dc3545;

        color: white;

        font-size: 10px;

        font-weight: 700;
    }



    /* =====================================================
       RIGHT - CONVERSATION
    ===================================================== */

    .chat-conversation {

        min-width: 0;

        display: flex;

        flex-direction: column;

        background: white;
    }



    /* HEADER */

    .chat-conversation-header {

        min-height: 70px;

        display: flex;

        align-items: center;

        padding:
            12px 20px;

        border-bottom:
            1px solid #dfe4e8;

        background: white;
    }


    .chat-selected-avatar {

        width: 44px;
        height: 44px;

        flex: 0 0 44px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-right: 12px;

        border-radius: 50%;

        background: #198754;

        color: white;

        font-weight: 700;
    }


    .chat-selected-name {

        font-size: 15px;

        font-weight: 700;
    }


    .chat-selected-status {

        margin-top: 3px;

        color: #198754;

        font-size: 11px;
    }



    /* MESSAGES */

    .chat-messages {

        flex: 1;

        overflow-y: auto;

        padding: 22px;

        background: #f4f7f9;
    }


    .chat-empty {

        height: 100%;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        color: #8795a1;

        text-align: center;
    }


    .chat-empty-icon {

        margin-bottom: 10px;

        font-size: 48px;

        opacity: .55;
    }



    /* MESSAGE */

    .chat-message-row {

        display: flex;

        margin-bottom: 13px;
    }


    .chat-message-row.mine {

        justify-content: flex-end;
    }


    .chat-message-row.theirs {

        justify-content: flex-start;
    }


    .chat-message {

        max-width: 65%;

        padding:
            10px 13px;

        border-radius: 12px;

        word-break: break-word;

        font-size: 13px;

        line-height: 1.45;
    }


    .chat-message.mine {

        color: white;

        background: #3898c4;

        border-bottom-right-radius:
            3px;
    }


    .chat-message.theirs {

        color: #30353a;

        background: white;

        border:
            1px solid #dfe4e8;

        border-bottom-left-radius:
            3px;
    }


    .chat-message-time {

        margin-top: 5px;

        font-size: 10px;

        opacity: .68;
    }



    /* =====================================================
       INPUT
    ===================================================== */

    .chat-compose {

        min-height: 78px;

        display: flex;

        align-items: center;

        gap: 10px;

        padding:
            13px 18px;

        border-top:
            1px solid #dfe4e8;

        background: white;
    }


    .chat-input {

        min-height: 46px;

        resize: none;
    }


    .chat-send-btn {

        min-width: 85px;

        height: 46px;
    }



    /* =====================================================
       LOADING
    ===================================================== */

    .chat-loading {

        padding: 25px;

        color: #88939c;

        font-size: 13px;

        text-align: center;
    }



    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (
        max-width: 1050px
    ) {

        .admin-chat-page {

            grid-template-columns:
                240px 1fr;
        }
    }

</style>

@endpush



@section('content')


<div class="chat-page-heading">

    <h1 class="chat-page-title">

        💬 Chat khách hàng

    </h1>


    <p class="chat-page-description">

        Xem tin nhắn và hỗ trợ khách hàng
        trực tiếp từ trang quản trị.

    </p>

</div>



<div class="admin-chat-page">


    {{-- =====================================================
         LEFT
    ===================================================== --}}

    <aside class="chat-users-panel">


        <div class="chat-users-header">

            <div class="chat-users-title">

                Khách hàng

            </div>


            <div
                id="chat-total-info"
                class="chat-users-subtitle"
            >

                Đang tải danh sách...

            </div>

        </div>



        <div class="chat-search-box">

            <input
                type="text"
                id="chat-user-search"
                class="
                    form-control
                    chat-search-input
                "
                placeholder="Tìm khách hàng..."
            >

        </div>



        <div
            id="chat-user-list"
            class="chat-user-list"
        >

            <div class="chat-loading">

                Đang tải...

            </div>

        </div>

    </aside>



    {{-- =====================================================
         RIGHT
    ===================================================== --}}

    <section class="chat-conversation">


        {{-- HEADER --}}
        <div class="chat-conversation-header">


            <div
                id="chat-selected-avatar"
                class="chat-selected-avatar"
                style="display:none;"
            >

                K

            </div>


            <div>

                <div
                    id="chat-selected-name"
                    class="chat-selected-name"
                >

                    Chọn khách hàng

                </div>


                <div
                    id="chat-selected-status"
                    class="chat-selected-status"
                    style="display:none;"
                >

                    ● Đang trò chuyện

                </div>

            </div>

        </div>



        {{-- MESSAGE --}}
        <div
            id="chat-messages"
            class="chat-messages"
        >

            <div class="chat-empty">

                <div class="chat-empty-icon">

                    💬

                </div>


                <strong>

                    Chưa chọn khách hàng

                </strong>


                <div class="mt-2">

                    Chọn một khách hàng bên trái
                    để xem cuộc trò chuyện.

                </div>

            </div>

        </div>



        {{-- INPUT --}}
        <div class="chat-compose">


            <textarea
                id="chat-input"
                class="
                    form-control
                    chat-input
                "
                rows="1"
                maxlength="2000"
                placeholder="Nhập tin nhắn..."
                disabled
            ></textarea>


            <button
                type="button"
                id="chat-send"
                class="
                    btn
                    btn-primary
                    chat-send-btn
                "
                disabled
            >

                Gửi

            </button>

        </div>

    </section>

</div>


@endsection



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const userList =
            document.getElementById(
                'chat-user-list'
            );


        const searchInput =
            document.getElementById(
                'chat-user-search'
            );


        const totalInfo =
            document.getElementById(
                'chat-total-info'
            );


        const selectedAvatar =
            document.getElementById(
                'chat-selected-avatar'
            );


        const selectedName =
            document.getElementById(
                'chat-selected-name'
            );


        const selectedStatus =
            document.getElementById(
                'chat-selected-status'
            );


        const messagesBox =
            document.getElementById(
                'chat-messages'
            );


        const messageInput =
            document.getElementById(
                'chat-input'
            );


        const sendButton =
            document.getElementById(
                'chat-send'
            );


        const csrfToken =
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content;


        const currentAdminId =
            {{ auth()->id() }};



        /*
        |--------------------------------------------------------------------------
        | STATE
        |--------------------------------------------------------------------------
        */

        let users =
            [];


        let selectedUserId =
            null;


        let selectedUserName =
            null;



        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(text)
        {
            const div =
                document.createElement(
                    'div'
                );


            div.textContent =
                text ?? '';


            return div.innerHTML;
        }



        /*
        |--------------------------------------------------------------------------
        | FORMAT TIME
        |--------------------------------------------------------------------------
        */

        function formatTime(value)
        {
            if (!value) {
                return '';
            }


            const date =
                new Date(value);


            if (
                Number.isNaN(
                    date.getTime()
                )
            ) {

                return '';
            }


            return date.toLocaleString(
                'vi-VN',
                {
                    hour:
                        '2-digit',

                    minute:
                        '2-digit',

                    day:
                        '2-digit',

                    month:
                        '2-digit',

                    year:
                        'numeric'
                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | USER AVATAR
        |--------------------------------------------------------------------------
        */

        function getInitial(name)
        {
            return (
                name
                ?
                name
                    .trim()
                    .charAt(0)
                    .toUpperCase()
                :
                'K'
            );
        }



        /*
        |--------------------------------------------------------------------------
        | RENDER USERS
        |--------------------------------------------------------------------------
        */

        function renderUsers()
        {
            const keyword =
                searchInput
                    .value
                    .trim()
                    .toLowerCase();


            const filtered =
                users.filter(
                    function (user) {

                        return (
                            (
                                user.name
                                || ''
                            )
                                .toLowerCase()
                                .includes(
                                    keyword
                                )
                            ||
                            (
                                user.email
                                || ''
                            )
                                .toLowerCase()
                                .includes(
                                    keyword
                                )
                        );
                    }
                );


            const totalUnread =
                users.reduce(
                    function (
                        total,
                        user
                    ) {

                        return (
                            total
                            +
                            Number(
                                user
                                    .unread_count
                                || 0
                            )
                        );
                    },
                    0
                );


            totalInfo.textContent =
                users.length
                +
                ' khách hàng'
                +
                (
                    totalUnread > 0
                        ?
                        ' · '
                        +
                        totalUnread
                        +
                        ' tin chưa đọc'
                        :
                        ''
                );


            if (
                filtered.length
                === 0
            ) {

                userList.innerHTML =
                    `
                    <div class="chat-loading">
                        Không tìm thấy khách hàng.
                    </div>
                    `;

                return;
            }


            userList.innerHTML =
                filtered.map(
                    function (user) {


                        const active =
                            Number(
                                user.id
                            )
                            ===
                            Number(
                                selectedUserId
                            );


                        const unread =
                            Number(
                                user
                                    .unread_count
                                || 0
                            );


                        return `
                            <button
                                type="button"
                                class="
                                    chat-user-item
                                    ${
                                        active
                                            ? 'active'
                                            : ''
                                    }
                                "
                                data-id="${user.id}"
                                data-name="${escapeHtml(user.name)}"
                            >

                                <div class="chat-user-avatar">

                                    ${escapeHtml(getInitial(user.name))}

                                </div>


                                <div class="chat-user-info">


                                    <div class="chat-user-name-row">


                                        <div class="chat-user-name">

                                            ${escapeHtml(user.name)}

                                        </div>


                                        ${
                                            unread > 0
                                                ?
                                                `
                                                <span class="chat-unread-badge">

                                                    ${unread}

                                                </span>
                                                `
                                                :
                                                ''
                                        }

                                    </div>


                                    <div class="chat-user-email">

                                        ${escapeHtml(user.email || '')}

                                    </div>

                                </div>

                            </button>
                        `;
                    }
                )
                .join('');


            document
                .querySelectorAll(
                    '.chat-user-item'
                )
                .forEach(
                    function (button) {

                        button.addEventListener(
                            'click',
                            function () {

                                selectUser(
                                    this.dataset.id,
                                    this.dataset.name
                                );
                            }
                        );
                    }
                );
        }



        /*
        |--------------------------------------------------------------------------
        | LOAD USERS
        |--------------------------------------------------------------------------
        */

        async function loadUsers(
            autoSelect = false
        ) {

            try {

                const response =
                    await fetch(
                        "{{ route('admin.chat.users') }}",
                        {
                            headers: {

                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'Không tải được danh sách khách hàng.'
                    );
                }


                users =
                    await response.json();


                renderUsers();


                /*
                |--------------------------------------------------------------------------
                | TỰ CHỌN USER ĐẦU TIÊN
                |--------------------------------------------------------------------------
                */

                if (
                    autoSelect
                    &&
                    !selectedUserId
                    &&
                    users.length > 0
                ) {

                    selectUser(
                        users[0].id,
                        users[0].name
                    );
                }


            } catch (error) {

                console.error(error);


                userList.innerHTML =
                    `
                    <div class="chat-loading text-danger">
                        Không tải được khách hàng.
                    </div>
                    `;
            }
        }



        /*
        |--------------------------------------------------------------------------
        | SELECT USER
        |--------------------------------------------------------------------------
        */

        function selectUser(
            id,
            name
        ) {

            selectedUserId =
                id;


            selectedUserName =
                name;


            selectedName.textContent =
                name;


            selectedAvatar.textContent =
                getInitial(name);


            selectedAvatar.style.display =
                'flex';


            selectedStatus.style.display =
                'block';


            messageInput.disabled =
                false;


            sendButton.disabled =
                false;


            renderUsers();


            loadMessages(
                true
            );


            messageInput.focus();
        }



        /*
        |--------------------------------------------------------------------------
        | LOAD MESSAGES
        |--------------------------------------------------------------------------
        */

        async function loadMessages(
            forceScroll = false
        ) {

            if (!selectedUserId) {
                return;
            }


            const nearBottom =
                (
                    messagesBox.scrollHeight
                    -
                    messagesBox.scrollTop
                    -
                    messagesBox.clientHeight
                )
                <
                100;


            try {

                const url =
                    "{{ route('admin.chat.messages', ['userId' => '__USER_ID__']) }}"
                        .replace(
                            '__USER_ID__',
                            selectedUserId
                        );


                const response =
                    await fetch(
                        url,
                        {
                            headers: {

                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'Không tải được tin nhắn.'
                    );
                }


                const messages =
                    await response.json();


                if (
                    messages.length
                    === 0
                ) {

                    messagesBox.innerHTML =
                        `
                        <div class="chat-empty">

                            <div class="chat-empty-icon">
                                💬
                            </div>

                            <strong>
                                Chưa có tin nhắn
                            </strong>

                            <div class="mt-2">
                                Hãy gửi lời chào tới khách hàng.
                            </div>

                        </div>
                        `;

                } else {

                    messagesBox.innerHTML =
                        messages.map(
                            function (
                                message
                            ) {


                                const mine =
                                    Number(
                                        message
                                            .sender_id
                                    )
                                    ===
                                    Number(
                                        currentAdminId
                                    );


                                const side =
                                    mine
                                        ?
                                        'mine'
                                        :
                                        'theirs';


                                return `
                                    <div
                                        class="
                                            chat-message-row
                                            ${side}
                                        "
                                    >

                                        <div
                                            class="
                                                chat-message
                                                ${side}
                                            "
                                        >

                                            <div>

                                                ${escapeHtml(message.content)}

                                            </div>


                                            <div class="chat-message-time">

                                                ${formatTime(message.created_at)}

                                            </div>

                                        </div>

                                    </div>
                                `;
                            }
                        )
                        .join('');
                }


                if (
                    forceScroll
                    ||
                    nearBottom
                ) {

                    messagesBox.scrollTop =
                        messagesBox
                            .scrollHeight;
                }


                /*
                |--------------------------------------------------------------------------
                | refresh unread
                |--------------------------------------------------------------------------
                */

                await loadUsers();


            } catch (error) {

                console.error(error);
            }
        }



        /*
        |--------------------------------------------------------------------------
        | SEND
        |--------------------------------------------------------------------------
        */

        async function sendMessage()
        {
            if (
                !selectedUserId
            ) {

                return;
            }


            const message =
                messageInput
                    .value
                    .trim();


            if (!message) {
                return;
            }


            sendButton.disabled =
                true;


            try {

                const response =
                    await fetch(
                        "{{ route('admin.chat.send') }}",
                        {

                            method:
                                'POST',


                            headers: {

                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken
                            },


                            body:
                                JSON.stringify(
                                    {

                                        user_id:
                                            selectedUserId,

                                        message:
                                            message
                                    }
                                )
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message
                        ||
                        data.error
                        ||
                        'Không gửi được tin nhắn.'
                    );
                }


                messageInput.value =
                    '';


                await loadMessages(
                    true
                );


            } catch (error) {

                alert(
                    error.message
                );

            } finally {

                sendButton.disabled =
                    false;


                messageInput.focus();
            }
        }



        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener(
            'input',
            renderUsers
        );



        /*
        |--------------------------------------------------------------------------
        | SEND CLICK
        |--------------------------------------------------------------------------
        */

        sendButton.addEventListener(
            'click',
            sendMessage
        );



        /*
        |--------------------------------------------------------------------------
        | ENTER TO SEND
        |--------------------------------------------------------------------------
        */

        messageInput.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key
                    ===
                    'Enter'
                    &&
                    !event.shiftKey
                ) {

                    event.preventDefault();


                    sendMessage();
                }
            }
        );



        /*
        |--------------------------------------------------------------------------
        | INITIAL
        |--------------------------------------------------------------------------
        */

        loadUsers(
            true
        );



        /*
        |--------------------------------------------------------------------------
        | POLLING 3 GIÂY
        |--------------------------------------------------------------------------
        */

        setInterval(
            function () {

                loadUsers();


                if (
                    selectedUserId
                ) {

                    loadMessages();
                }

            },
            3000
        );

    }
);

</script>

@endpush