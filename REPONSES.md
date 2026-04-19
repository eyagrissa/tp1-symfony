Reponses TP3 Securite Symfony

1) Un Password Hasher sert a chiffrer le mot de passe avant de le stocker en base de données. Symfony utilise auto pour choisir automatiquement le meilleur algorithme disponible.

2) La section providers indique a Symfony comment charger un utilisateur depuis la base de données a partir de son email par exemple.

3) On utilise plainPassword car il nest pas stocké en base de données, il sert juste a recuperer le mot de passe saisi puis le hasher. Si on stockait en clair nimporte qui avec acces a la BDD verrait tous les mots de passe.

4) app est une variable globale Twig de Symfony qui donne acces a la session, la requete et lutilisateur connecte. app.user retourne lutilisateur connecte ou null si personne nest connecte.

5) Lauthentification cest verifier qui tu es (login/mot de passe). Lautorisation cest verifier ce que tu as le droit de faire (ROLE_USER, ROLE_ADMIN).

6) La hierarchie de roles permet a un role dheriter des droits dun autre. Pour que ROLE_ADMIN ait les droits de ROLE_USER on ajoute dans security.yaml :
role_hierarchy:
    ROLE_ADMIN: ROLE_USER
