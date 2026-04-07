export const CryptoUtil = {
    async generateKey() {
        const key = await window.crypto.subtle.generateKey(
            { name: "AES-GCM", length: 256 },
            true,
            ["encrypt", "decrypt"]
        );
        const exportedKey = await window.crypto.subtle.exportKey("raw", key);
        return {
            key,
            base64: this.arrayBufferToBase64(exportedKey)
        };
    },

    async importKey(base64Key) {
        const rawKey = this.base64ToArrayBuffer(base64Key);
        return await window.crypto.subtle.importKey(
            "raw",
            rawKey,
            "AES-GCM",
            true,
            ["encrypt", "decrypt"]
        );
    },

    async encryptText(text, keyItem) {
        let cryptoKey = keyItem;
        if (typeof keyItem === 'string') cryptoKey = await this.importKey(keyItem);

        const iv = window.crypto.getRandomValues(new Uint8Array(12));
        const encodedText = new TextEncoder().encode(text);

        const encryptedContent = await window.crypto.subtle.encrypt(
            { name: "AES-GCM", iv: iv },
            cryptoKey,
            encodedText
        );

        return {
            encrypted: this.arrayBufferToBase64(encryptedContent),
            iv: this.arrayBufferToBase64(iv)
        };
    },

    async decryptText(encryptedBase64, ivBase64, keyItem) {
        let cryptoKey = keyItem;
        if (typeof keyItem === 'string') cryptoKey = await this.importKey(keyItem);

        const iv = this.base64ToArrayBuffer(ivBase64);
        const encryptedContent = this.base64ToArrayBuffer(encryptedBase64);

        const decryptedContent = await window.crypto.subtle.decrypt(
            { name: "AES-GCM", iv: iv },
            cryptoKey,
            encryptedContent
        );

        return new TextDecoder().decode(decryptedContent);
    },

    async encryptFile(file, keyItem) {
        let cryptoKey = keyItem;
        if (typeof keyItem === 'string') cryptoKey = await this.importKey(keyItem);

        const arrayBuffer = await file.arrayBuffer();
        const iv = window.crypto.getRandomValues(new Uint8Array(12));

        const encryptedContent = await window.crypto.subtle.encrypt(
            { name: "AES-GCM", iv: iv },
            cryptoKey,
            arrayBuffer
        );

        return {
            blob: new Blob([encryptedContent]),
            iv: this.arrayBufferToBase64(iv)
        };
    },

    async decryptFile(encryptedBlob, ivBase64, keyItem, mimeType) {
        let cryptoKey = keyItem;
        if (typeof keyItem === 'string') cryptoKey = await this.importKey(keyItem);

        const arrayBuffer = await encryptedBlob.arrayBuffer();
        const iv = this.base64ToArrayBuffer(ivBase64);

        const decryptedContent = await window.crypto.subtle.decrypt(
            { name: "AES-GCM", iv: iv },
            cryptoKey,
            arrayBuffer
        );

        return new Blob([decryptedContent], { type: mimeType });
    },

    arrayBufferToBase64(buffer) {
        let binary = '';
        const bytes = new Uint8Array(buffer);
        const len = bytes.byteLength;
        for (let i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    },

    base64ToArrayBuffer(base64) {
        const binary_string = window.atob(base64);
        const len = binary_string.length;
        const bytes = new Uint8Array(len);
        for (let i = 0; i < len; i++) {
            bytes[i] = binary_string.charCodeAt(i);
        }
        return bytes.buffer;
    }
};
