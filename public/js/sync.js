// window.addEventListener("online", () => {
//     syncOfflineRequests();
// });

// async function syncOfflineRequests() {

//     const dbReq = indexedDB.open("aulaDB", 1);

//     dbReq.onsuccess = async e => {
//         const db = e.target.result;
//         const tx = db.transaction("offline_requests", "readwrite");
//         const store = tx.objectStore("offline_requests");

//         const all = store.getAll();

//         all.onsuccess = async () => {
//             const datos = all.result;

//             for (let data of datos) {
//                 await fetch(data.url || "/formulario/submit", {
//                     method: "POST",
//                     headers: { "Content-Type": "application/json" },
//                     body: JSON.stringify(data)
//                 });
//                 store.delete(data.id);
//             }
//         };
//     };
// }
