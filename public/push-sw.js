self.addEventListener("push", function (event) {
    console.log("Push event received:", event);
    if (!event.data) {
        console.log("No data received");
        return;
    }

    let data;
    try {
        const text = event.data.text();
        console.log("Text:", text);
        try {
            data = JSON.parse(text);
            console.log("Data:", data);
        } catch (e) {
            data = { title: "Notification", body: text };
            console.log("Data:", data);
        }
    } catch (e) {
        data = {};
    }
    console.log("Data:", data);

    const title = data.title || "Notification";
    const options = {
        body: data.body || "",
        icon: data.icon || "/favicon.ico",
        data: data.data || {},
        actions: data.actions || [],
    };
    console.log("Options:", options);

    const notifyClientsPromise = self.clients
        .matchAll({ includeUncontrolled: true, type: "window" })
        .then(function (clients) {
            console.log("Found clients:", clients.length);
            if (clients.length === 0) {
                console.log("No clients found to send message to");
            }
            clients.forEach(function (client) {
                console.log("Sending message to client:", client.url);
                try {
                    client.postMessage({
                        type: "order-notification",
                        payload: data,
                    });
                    console.log('Order Notification sent.');
                } catch (error) {
                    console.error("Failed to send message to client:", error);
                }
            });
        })
        .catch(function (error) {
            console.error("Failed to match clients:", error);
        });

    event.waitUntil(
        Promise.all([
            self.registration.showNotification(title, options),
            notifyClientsPromise,
        ])
    );
});

self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    const url =
        event.notification.data && event.notification.data.url
            ? event.notification.data.url
            : "/";

    event.waitUntil(clients.openWindow(url));
});

