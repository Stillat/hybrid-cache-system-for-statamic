# Hybrid Cache for Statamic Example Repository

This repository contains the source code developed in a series of blog posts:

<strong>[Creating a Hybrid Cache System for Statamic](https://stillat.com/blog/2023/09/03/creating-a-hybrid-cache-system-for-statamic)</strong>

Part 1 of 6 covers experimenting with various cache systems, techniques, and organization of our custom cache system

<strong>[Creating a Hybrid Cache System for Statamic: Part Two](https://stillat.com/blog/2023/09/03/creating-a-hybrid-cache-system-for-statamic-part-two)</strong>

Part 2 of 6 covers invalidating cached responses when template changes are detected and detecting request content dependencies, such as entries, taxonomies, and assets.

<strong>[Creating a Hybrid Cache System for Statamic: Part Three](https://stillat.com/blog/2023/09/03/creating-a-hybrid-cache-system-for-statamic-part-three)</strong>

Part 3 of 6 covers managing response headers, configuring the custom cache system, and implementing mechanisms to bypass the cache entirely.

<strong>[Creating a Hybrid Cache System for Statamic: Part Four](https://stillat.com/blog/2023/09/03/creating-a-hybrid-cache-system-for-statamic-part-four)</strong>

Part 4 of 6 covers implementing new features to set arbitrary expiration times from within a template and mechanisms to invalidate all cached responses at once.

<strong>[Creating a Hybrid Cache System for Statamic: Part Five](https://stillat.com/blog/2023/09/03/creating-a-hybrid-cache-system-for-statamic-part-five)</strong>

Part 5 of 6 covers implementing a cache namespace and labeling system, which we can use to target multiple cache entries simultaneously for invalidation.

<strong>[Creating a Hybrid Cache System for Statamic: Part Six](https://stillat.com/blog/2023/09/03/creating-a-hybrid-cache-system-for-statamic-part-six)</strong>

Part 6 of 6 covers implementing custom Artisan commands to retrieve information about our cache, invalidating responses returned from Laravel routes and controllers, excluding pages with CSRF tokens, and examples of how to integrate with third-party systems like Torchlight.

## License

The hybrid cache code sample is free software released under the MIT License.
