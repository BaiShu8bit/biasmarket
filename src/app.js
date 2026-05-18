const express = require("express");
const cors = require("cors");
const app = express();

app.use(cors());

const groupsRoutes = require("./routes/groups.routes");
const membersRoutes = require("./routes/members.routes");
const albumsRoutes = require("./routes/albums.routes");
const photocardsRoutes = require("./routes/photocards.routes");

app.use(express.json());

// rutas
app.use("/api/groups", groupsRoutes);
app.use("/api/members", membersRoutes);
app.use("/api/albums", albumsRoutes);
app.use("/api/photocards", photocardsRoutes);

module.exports = app;