Neo4j
=====

Experiment with talking to a Neo4j database

Requires the following things to work:

 - a Neo4j database on its default port
 - several nodes having a property "type" of either "Book" or "Person"
    - one of which must have id=1, as I don't yet have it reading from an index
 - some relationships between Person and Book of type "likes"
 - some relationships between Person and Person of type "trusts"

It does *basic* stuff just as a proof of concept (most advanced thing is a lazy loading implementation for Node properties)
