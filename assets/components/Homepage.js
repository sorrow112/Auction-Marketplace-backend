import {
  Card,
  CardContent,
  Container,
  Grid,
  Toolbar,
  Typography,
} from "@mui/material";
import { Box, fontWeight } from "@mui/system";
import React from "react";
import image from "../media/images/homepageTopImage.jpg";

import HomePageList from "./customComponents/HomePageList";

const encheresInverses = {
  1: { nom: "pc", vendeur: "exemple123", prix: 80 },
  2: { nom: "pc", vendeur: "exemple123", prix: 80 },
  3: { nom: "pc", vendeur: "exemple123", prix: 80 },
  4: { nom: "pc", vendeur: "exemple123", prix: 80 },
};

const encheres = {
  1: { nom: "pc", vendeur: "exemple123", prix: 80 },
  2: { nom: "pc", vendeur: "exemple123", prix: 80 },
  3: { nom: "pc", vendeur: "exemple123", prix: 80 },
  4: { nom: "pc", vendeur: "exemple123", prix: 80 },
};
const ventes = {
  1: { nom: "pc", vendeur: "exemple123", prix: 80 },
  2: { nom: "pc", vendeur: "exemple123", prix: 80 },
  3: { nom: "pc", vendeur: "exemple123", prix: 80 },
  4: { nom: "pc", vendeur: "exemple123", prix: 80 },
  5: { nom: "pc", vendeur: "exemple123", prix: 80 },
  6: { nom: "pc", vendeur: "exemple123", prix: 80 },
  7: { nom: "pc", vendeur: "exemple123", prix: 80 },
  8: { nom: "pc", vendeur: "exemple123", prix: 80 },
};

const styles = {
  subDiv: {
    maxWidth: "100%",
    minHeight: 500,
    backgroundImage: `url(${image})`,
    backgroundSize: "cover",
    backgroundRepeat: "no-repeat",
  },
  generalText: {
    textAlign: "center",
  },
  textDiv: {
    width: "60%",
  },
  servicesHr: {
    margin: "auto",
    width: 70,
    position: "relative",
    borderTop: "2px solid info.main",
    marginTop: 5,
  },
  mainDiv: {
    backgroundColor: "primary.main",
    padding: 0,
  },
  servicesGrid: {
    justifyContent: "space-between",
    width: "70%",
    margin: "auto",
  },
};

const Homepage = () => {
  return (
    <Box sx={styles.mainDiv}>
      {/* section #1 */}
      <Box sx={styles.subDiv}>
        <Container sx={styles.textDiv}>
          <Typography
            variant="h2"
            gutterBottom
            sx={{ ...styles.generalText, fontWeight: "bolder" }}
          >
            bienvenu a notre marketplace
          </Typography>
          <Typography
            variant="h5"
            gutterBottom
            sx={{ ...styles.generalText, mt: "30%" }}
          >
            ici vous pouvez trouver les produits rares et communs, et vous
            pouvez aussi deposer votre produit dans un enchère pour un benefice
            maximal, et on a beaucoup plus a offrir !
          </Typography>
        </Container>
      </Box>

      {/* section #2 */}
      <Box sx={{ mt: "10%", textAlign: "center" }}>
        <Container>
          <Typography variant="h2">NOS SERVICES</Typography>
          <hr style={styles.servicesHr} />
        </Container>
        <br />
        <br />

        <Grid container sx={styles.servicesGrid}>
          <Grid item xs={3}>
            <Card sx={{ minWidth: 275 }}>
              <CardContent>
                <Typography variant="h5">enchères</Typography>
                <Typography>
                  enchérer competetivement pour acheter un produit
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={3}>
            <Card sx={{ minWidth: 275 }}>
              <CardContent>
                <Typography variant="h5">enchères inversés</Typography>
                <Typography>
                  enchérer competetivement pour vendre un produit
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={3}>
            <Card sx={{ minWidth: 275 }}>
              <CardContent>
                <Typography variant="h5">demande de devis</Typography>
                <Typography>
                  demander d'un fournisseur une proposition sur un produit
                  specifique
                </Typography>
              </CardContent>
            </Card>
          </Grid>
        </Grid>
      </Box>
      {/* section #3 */}
      <Box sx={{ mt: "10%", textAlign: "center" }}>
        <Typography variant="h4">nos produits</Typography>
        <HomePageList ventes={ventes} />
        <Grid container >
          <Grid item xs={5.5}>
            <Typography variant="h4">nos enchères</Typography>
            <HomePageList ventes={encheres} />
          </Grid>
          <Grid xs={1}></Grid>
          <Grid item xs={5.5}>
            <Typography variant="h4">nos enchères inverses</Typography>
            <HomePageList ventes={encheresInverses} />
          </Grid>
        </Grid>
      </Box>
    </Box>
  );
};

export default Homepage;
